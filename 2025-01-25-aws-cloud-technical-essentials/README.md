# Coursera: AWS Cloud Technical Essential: Generative AI Services on AWS.

See https://www.coursera.org/learn/aws-cloud-technical-essentials/lecture/oxssZ/introduction-to-amazon-bedrock.

## Introduction to Amazon Bedrock

There are many common use cases for generative AI: 

* Virtual assistants
* Fraud detection
* Customer service
* Code generation
* Software development

Generative AI is a type of AI that can create new content when prompted to do so. This content is based on the data that the underlying model is trained on.

**Large Language Models (LLMS)**:

* Designed for tasks revolving around natural language.
* Trained on very large data sets.
* Great for language-related tasks.

**Foundation Models (FMs)**:

* They are *base models*.
* Massive ML models that are trained for general purpose tasks.
* Should be fine-tuned with local data to improve performance for specific tasks.

So what is **Amazon Bedrock**? It's a fully-managed service that allow you to access a variety of FMs through APIs. There are many models that differ in capabilities and specialties.

One category of FMs through Amazon Bedrock is the **Amazon Titan** family of models. These models are general-purpose and can support a variety of use cases. So they would need to be fine-tuned for your needs.

To fine-tune a model, you can:

* Create a continued pre-training model, which means you can train a model with new unlabeled data.
* Fine-tune a model, which means you can improve a model's performance on specific tasks by providing a training data set of labeled examples.
* Create knowledge bases by uploading your own custom data sources that create a repo of information that are then used to augment an FM's responses.
  * This allows you to reference proprietary information.
  * It uses Retrieval Augmented Generation (RAG).
  * This can help reduce AI hallucinations.

You can utilize these models by calling the Amazon Bedrock APIs, using the AWS SDKs, and providing the correct parameters.

## Introduction to Amazon Q

Amazon Q is a generative AI chat bot powered by Amazon Bedrock. It is trained on 17 years worth of AWS knowledge and experience, so it can:

* Help solve problems and complete tasks with AWS, like generating code and tests
* Troubleshoot and diagnose issues with your AWS solution
* Walk you through common AWS architectures and best practices
* General Q&A sessions on AWS or specific business topics
* Do multi-step planning and reasoning that can transform and implement new code generated from developer requests.

It is an expert in general AWS knowledge, AWS QuickSight, Amazon Connect, and soon AWS supply chain.

* Integration with AWS QuickSight makes it easier to generate visuals and dashboards to drive decision-making.
* Integration with Amazon Connect allows customer service to automatically detect customer intent during calls and chats.

Amazon Q Business can also connect to your own data sources to become an expert on your business. This would include integrating with Microsft Sharepoint, Google Drive, Confluence, Jira, Microsoft Teams, Slack, and more.

## Amazon Q Developer Basics

