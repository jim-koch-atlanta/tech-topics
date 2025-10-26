# Coursera: Fundamentals of AI Agents Using RAG and LangChain

See https://www.coursera.org/learn/fundamentals-of-ai-agents-using-rag-and-langchain/ungradedLti/XqGWi/lab-rag-with-pytorch.

## Lab: RAG with PyTorch

> As a machine learning engineer hired by a social media company, your task is determining whether songs shared on the platform are appropriate for children. Given the high costs associated with processing each song using large language models (LLMs) for content evaluation, an alternative method using retrieval-augmented generation (RAG) is proposed. RAG combines the benefits of a retriever model, which fetches relevant information (in this case, embeddings of pre-answered content appropriateness questions), and a generator model, which uses this information to predict the appropriateness of new content. This approach efficiently scales the evaluation process while ensuring that each song's content is scrutinized for child safety without the overhead of running a full LLM for each song.

## Summary

* RAG is an AI framework that helps optimize the output of large language models or LLMs.
* RAG combines retrieved information and generates natural language to create responses.
* RAG consists of two main components: the retriever, the core of RAG, and the generator, which functions as a chatbot.
* In RAG process:
  * The retriever encodes user-provided prompts and relevant documents into vectors, stores them in a vector database, and retrieves relevant context vectors based on the distance between the encoded prompt and documents. 
  * The generator then combines the retrieved context with the original prompt to produce a response.  
* The Dense Passage Retrieval (or DPR) Context Encoder and its tokenizer focus on encoding potential answer passages or documents. This encoder creates embeddings from extensive texts, allowing the system to compare these with question embeddings to find the best match.
* Facebook AI Similarity Search, also known as Faiss, is a library developed by Facebook AI Research that offers efficient algorithms for searching through large collections of high-dimensional vectors.
* Faiss is essentially a tool to calculate the distance between the question embedding and the vector database of context vector embeddings.
* The DPR question encoder and its tokenizer focus on encoding the input questions into fixed-dimensional vector representations, grasping their meaning and context to facilitate answering them.

## Next

https://www.coursera.org/learn/fundamentals-of-ai-agents-using-rag-and-langchain/lecture/uRc3Y/introduction-to-langchain