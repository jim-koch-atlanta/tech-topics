def warn(*args, **kwargs):
    pass
import warnings
warnings.warn = warn
warnings.filterwarnings('ignore')

from langchain.document_loaders import TextLoader
from langchain.text_splitter import CharacterTextSplitter
from langchain.vectorstores import Chroma
from langchain.embeddings import HuggingFaceEmbeddings
from langchain.chains import RetrievalQA
from langchain.prompts import PromptTemplate
from langchain.chains import ConversationalRetrievalChain
from langchain.memory import ConversationBufferMemory

from ibm_watsonx_ai.foundation_models import Model
from ibm_watsonx_ai.metanames import GenTextParamsMetaNames as GenParams
from ibm_watsonx_ai.foundation_models.utils.enums import ModelTypes, DecodingMethods
from ibm_watson_machine_learning.foundation_models.extensions.langchain import WatsonxLLM
import wget
import os

# Download and load the document
filename = 'companyPolicies.txt'
url = 'https://cf-courses-data.s3.us.cloud-object-storage.appdomain.cloud/6JDbUb_L3egv_eOkouY71A.txt'

# Use wget to download the file. Do not download if it already exists.
if not os.path.exists(filename):
    wget.download(url, out=filename)
    print('file downloaded')

# Split the document into chunks
loader = TextLoader(filename)
documents = loader.load()
text_splitter = CharacterTextSplitter(chunk_size=1000, chunk_overlap=0)
texts = text_splitter.split_documents(documents)
print(f'Number of document chunks: {len(texts)}')

# Embedding and storing
# In this step, we are converting the text chunks into vector embeddings and storing them in a vector database.
embeddings = HuggingFaceEmbeddings()
docsearch = Chroma.from_documents(texts, embeddings)  # store the embedding in docsearch using Chromadb
print('document ingested')

# LLM model construction
# In this step, we'll build an LLM model from IBM watsonx.ai. We use the 'ibm/granite-3-3-8b-instruct' model, and we define the parameters for our model.
model_id = 'ibm/granite-3-3-8b-instruct'
parameters = {
    GenParams.DECODING_METHOD: DecodingMethods.GREEDY,
    GenParams.MIN_NEW_TOKENS: 130,
    GenParams.MAX_NEW_TOKENS: 256,
    GenParams.TEMPERATURE: 0.5
}

# Get an API key from https://cloud.ibm.com/iam/apikeys.
apiKey_file = 'apiKey.txt'
with open(apiKey_file, 'r') as file:
    api_key = file.read().strip()

# Get a project ID from https://cloud.ibm.com/projects.
projectId_file = 'projectId.txt'
with open(projectId_file, 'r') as file:
    project_id = file.read().strip()

credentials = {
    "url": "https://us-south.ml.cloud.ibm.com",
    "apikey": api_key,
}

model = Model(
    model_id=model_id,
    params=parameters,
    credentials=credentials,
    project_id=project_id
)

flan_ul2_llm = WatsonxLLM(model=model)
print('LLM model loaded')

# Integrate LangChain with watsonx.ai LLM
# In this step, we integrate the watsonx.ai LLM with LangChain to create a conversational retrieval chain.
qa = RetrievalQA.from_chain_type(llm=flan_ul2_llm, 
                                 chain_type="stuff", 
                                 retriever=docsearch.as_retriever(), 
                                 return_source_documents=False)
query = "what is mobile policy?"
qa.invoke(query)

# Ask a more high-level question.
qa = RetrievalQA.from_chain_type(llm=flan_ul2_llm, 
                                 chain_type="stuff", 
                                 retriever=docsearch.as_retriever(), 
                                 return_source_documents=False)
query = "Can you summarize the document for me?"
qa.invoke(query)

model_id = 'ibm/granite-3-3-8b-instruct'

parameters = {
    GenParams.DECODING_METHOD: DecodingMethods.GREEDY,  
    GenParams.MAX_NEW_TOKENS: 256,  # this controls the maximum number of tokens in the generated output
    GenParams.TEMPERATURE: 0.5 # this randomness or creativity of the model's responses
}

credentials = {
    "url": "https://us-south.ml.cloud.ibm.com"
}

project_id = "skills-network"

model = Model(
    model_id=model_id,
    params=parameters,
    credentials=credentials,
    project_id=project_id
)

llama_3_llm = WatsonxLLM(model=model)
print('LLM model loaded')

# How can we add the prompt in retrieval using LangChain?
# In this step, we define a custom prompt template to guide the LLM in answering questions based on the retrieved document context.

# The `context` variable contains the relevant information from the document, and the `question` variable contains the user's query.
prompt_template = """Use the information from the document to answer the question at the end. If you don't know the answer, just say that you don't know, definately do not try to make up an answer.

{context}

Question: {question}
"""

PROMPT = PromptTemplate(
    template=prompt_template, input_variables=["context", "question"]
)

chain_type_kwargs = {"prompt": PROMPT}

qa = RetrievalQA.from_chain_type(llm=llama_3_llm, 
                                 chain_type="stuff", 
                                 retriever=docsearch.as_retriever(), 
                                 chain_type_kwargs=chain_type_kwargs, 
                                 return_source_documents=False)

query = "Can I eat in company vehicles?"
qa.invoke(query)

# Conversational Retrieval Chain with Memory
# In this case, the LLM does not have memory of previous interactions, so it does not what "it" refers to.
query = "What can I not do in it?"
qa.invoke(query)

# To enable the model to remember previous interactions, we can use a conversational retrieval chain with memory.
memory = ConversationBufferMemory(memory_key = "chat_history", return_message = True)

qa = ConversationalRetrievalChain.from_llm(llm=llama_3_llm, 
                                           chain_type="stuff", 
                                           retriever=docsearch.as_retriever(), 
                                           memory = memory, 
                                           get_chat_history=lambda h : h, 
                                           return_source_documents=False)

history = []

query = "What is mobile policy?"
result = qa.invoke({"question":query}, {"chat_history": history})
print(result["answer"])

history.append((query, result["answer"]))

# Now, we can ask follow-up questions and the model will remember the context from previous interactions.
query = "What is the aim of it?"
result = qa({"question": query}, {"chat_history": history})
print(result["answer"])