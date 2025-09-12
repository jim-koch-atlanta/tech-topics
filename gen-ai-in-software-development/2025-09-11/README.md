# Coursera: Generative AI in Software Development

See https://www.coursera.org/learn/generative-ai-in-software-development/lecture/O943a/ai-in-customer-relationship-management-crm.

## AI in Customer Relationship Management (CRM)

Traditional CRMs have their limitations:
* They can't dig deeper into data.
* They can't predict what a customer might need next.

With AI, a CRM can analyze and process the stored data. They can use it to create tailored experience to anticipate needs and offer personalized support.

AI integration could offer *hyper-personalized campaigns* that are targeted to the individual history of each customer.

AI can recognize tone, facial expressions, or gestures to offer *emotional intelligence*.

The result is that AI can focus on the busy work, allowing teams to focus on creative work.

## Integration with existing systems

There are several key considerations when integrating AI into an existing software system.

### Compatibility

Often times, older systems lack the flexibility to support advanced AI functionalities. The solution is usually middleware or APIs that can help bridge the gap. Some example platforms are Zapier or MuleSoft, which facilitate data flow and integration.

### Workflow adaptation

People are often resistent to change. One option to alleviate this issue is a phased implementation of AI features. Start small with a pilot project that shocases the value of AI without overhauling everything at once. Also, offer training to help employees understand how the tools work.

### Data integration and quality

Often, data is stored across many different systems and formats. The solution is to utilize data transformation tools and centralized data warehouses to consolidate and clean the data.

### Computation resource requirements

A key consideration for AI work is whether to use *local machines* or *cloud deployments*.

Local machines offer the benefit of providing greater control, especially when dealing with sensitive data. They also reduce latency, so they are ideal for applications that require real-time responses.

However, they have a significant upfront investment in hardware.

Cloud-based solutions prioritize scalability and flexibility. So the costs can start cheap and spin up on-demand.

However, costs can escalate quickly, particularly for compute-heavy tasks like training LLM models.

## Ethical and regulatory compliance

AI algorithms are only as good as the data they're trained on. If they're trained on flawed, biased, or incomplete data, the AI could perpetuate those same biases.

It's important to diversity databases, audit outputs, and involve diverse teams when developing AI solutions.

Techniques like *explainable AI* (XAI) can help make AI systems more transparent and understandable.

## Next

https://www.coursera.org/learn/generative-ai-in-software-development/lecture/Erdn8/scalability