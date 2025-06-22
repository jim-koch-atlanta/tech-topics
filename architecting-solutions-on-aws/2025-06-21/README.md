# Coursera: Architecting Solutions on AWS

See https://www.coursera.org/learn/architecting-solutions-on-aws/lecture/yRJkX/week-4-introduction.

## Customer #4: Use Case & Requirements

In this scenario, the customer is a marketing agency that creates websites for companies. They have a single AWS account called the "prod" account, and they run all of the infrastructure from within this account and environment. Because it's all running under one account, they risk accidentally modifying the wrong resources when making updates.

When it comes to management and billing, they try to handle this by applying tags to the different pieces of infrastructure. However, this is inconsistent and error-prone. Especially now, other employees are gaining administrative access for the AWS account, and there are concerns that things could quickly get out of hand.

AWS provides services to support a multi-account strategy with proper governance and security guardrails.

The intended path forward includes these requirements:

1. The need for multiple accounts with automatic account provisioning.

2. A shared services account that people will initially log in.

3. Single sign-on amount the accounts.

4. Security guardrails on these accounts.

5. An account for logging, like security or API activity. This is a best practice.

## Customer #4: Requirements Breakdown

This is a typical scenario where cloud is not the customer's main expertise.

**Requirement**: Our customer will benefit from well-defined governance standards by having **multiple AWS accounts to organize resources**. There are ways to group AWS services into separate accounts.

**Requirement**: Enabling **a shared services account that the admins will initially log into**. When users log into these accounts, they can then use SSO to log into other accounts. That's needed so that we don't need to replicate user credentials among all accounts.

**Requirement**: Set automatic accounts provisioning and enforce configuration standards for newly created accounts.

**Requirement**: Have an account for centralized logging, which is a security best practice.

## Why Multi-Account Strategies?

There are many ways that resources can be grouped together. One approach is to group workloads based on business purpose and ownership. **Business unit isolation can help teams operate with greater decentralized control while still having security guardrails.** This makes sense for our scenario, since our customer is a marketing agency running workloads for other companies.

Splitting into separate accounts facilitates governance and access, making it easier to limit access to only members from the Cloud Center of Excellence.

Also, billing in AWS is naturally charged per account, so it facilitates billing to have multiple accounts.

## Multi-Account Strategies

The practice of using multiple accounts has manage advantages:

* Group workloads based on business purpose and ownership
* Centralize logging
* Constrain access to sensitive data
* Limit the scope of impact from adverse events
* Manage costs better
* Distribute AWS service quotas and API request rate limits

### Group workloads based on business purpose and ownership

By isolating business units, they can operate with greater decentralized control, while still retaining the ability to provide overarching guardrails. This approach will also ease divestment of those units over time.

Guardrails are governance rules for security, operations, and compliance that you can define and apply to align with your overall requirements.

### Apply distinct security controls by environment

As an example, it's common to apply different policies for security and operations to the non-production and production environments of a given workload. If you use separate accounts for these environments, the resources and data that make up the environment are separate from other environments by default.

### Constrain access to sensitive data

When you limit sensitive data stores to an account that is built to manage it, you can more easily constrain the number of people and process that can access and manage the data. This simplifies least-privilege access.

### Promote innovation and agility

In the early stages of a workload’s lifecycle, you can help promote innovation by providing your builders (engineers) with separate accounts in support of experimentation, development, and early testing. These accounts provide broader access to AWS services while also using guardrails that help prohibit access to (and the use of) sensitive and internal data.

*Sandbox accounts*: Typically disconnected from your enterprise services and don’t provide access to your internal data. However, they offer the greatest freedom for experimentation.

*Development accounts*: Typically provide limited access to your enterprise services and development data. However, they can more readily support day-to-day experimentation with your enterprise-approved AWS services, formal development, and early testing work.

In both cases, security guardrails and cost budgets are strongly recommended.

### Limit scope of impact from adverse events

By design, all resources that are provisioned within one account are logically separated from resources provisioned within other accounts.

This isolation boundary limits the risks of an application-related issue, misconfiguration, or malicious actions.

### Support multiple IT operating models

Operating models are ways that responsibility can be divided among parts of an organization, in order to deliver application workloads and platform capabilities. It's possible for one organization to support multiple operating models.

![](image1.png)

In the *Traditional Ops* model, dev teams are responsible for engineering their applications, but not for their production operations. A cloud platform engineering team is responsible for engineering the underlying platform capabilities. A separate cloud operations team is responsible for the operations of both applications and platform.

In the *CloudOps* model, dev teams are also responsible for production operations of their applications. In this model, a common cloud platform engineering team is responsible for both the engineering and operations of the underlying platform capabilities.

In the *DevOps* model, the application teams take on the additional responsibilities of engineering and operating platform capabilities that are specific to their applications. A cloud platform engineering team is responsible for the engineering and operations of shared platform capabilities that are used by multiple applications.

### Manage costs

An account is the default way that AWS costs are allocated. Therefore, using different accounts for different business units can simplify reporting, forecasting, and budgeting.

When fine-grained cost allocation is required, you can apply cost allocation tags to individual resources.

### Distribute AWS service quotes and API request rate limits

Service quotas and request rate limits are allocated for each account. Therefore, using separate accounts for workloads helps distribute that impact.

## Next

https://www.coursera.org/learn/architecting-solutions-on-aws/lecture/hnLKt/iam-roles-the-aws-authentication-core-mechanism