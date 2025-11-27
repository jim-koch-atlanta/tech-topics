# Coursera: Fundamentals of AI Agents Using RAG and LangChain

See https://www.coursera.org/learn/fundamentals-of-ai-agents-using-rag-and-langchain/ungradedLti/Bsvrm/lab-in-context-engineering-and-prompt-templates.

## Lab: In-Context Learning and Prompt Templates

> You're stepping into the world of prompt engineering, where each command you craft has the power to guide intelligent LLM systems toward specific outcomes. In this tutorial, you will explore the foundational aspects of prompt engineering, dive into advanced techniques of in-context learning, such as few-shot and self-consistent learning, and learn how to effectively use tools like Langchain.
> 
> Start by understanding the basics—how to formulate prompts that communicate effectively with AI. From there, we'll explore how the Langchain prompt template can simplify and enhance this process, making it more structured and efficient.
> 
> As you progress, you'll learn to apply these skills in practical scenarios, creating sophisticated applications like QA bots and text summarization tools. By using the Langchain prompt template, you'll see firsthand how structured prompting can streamline the development of these applications, transforming complex requirements into clear, concise tasks for AI.

## LangChain: Core Concepts

LangChain is an open-source library that consists of several key components -- Documents, Chains, Agents, Language Model, Chat Model, Chat Message, Prompt Templates, and Output Parsers.

**Language Models** are the interface for interacting with LLMs. LangChain supports IBM, OpenAI, Google, and Meta as its primary language models. In addition to the model ID, the Language Model is also customized by setting parameters, credentials, and a project ID.

**Chat Models** are a type of language model. They are designed for efficient conversations. It understands a question and responds like a human. Chat models use a few message types for controlling conversational flow -- the HumanMessage (for user input), the AIMessage (for model's responses), the SystemMessage (for instructing the model), the ToolMessage (for tool interaction), and the FunctionMessage (for function calling).

**Prompt Templates** translate user's questions or messages into clear instructions. There are several types:
* StringPrompt template
* ChatPrompt template
* MessagePrompt template
* Messages placeholder
* FewShot prompt template

**Output Parser** transform the output from LLM into data formats useful for data-handling needs. For example, it can structure the output into JSON, XML, or CSV for the next step of the system.

## Next

https://www.coursera.org/learn/fundamentals-of-ai-agents-using-rag-and-langchain/lecture/suHxQ/langchain-documents-for-building-rag-applications