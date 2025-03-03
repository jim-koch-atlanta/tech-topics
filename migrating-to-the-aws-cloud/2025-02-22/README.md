# Coursera: Migrating to the AWS Cloud: Assessing Review

See https://www.coursera.org/learn/aws-fundamentals-migrating-to-the-cloud/lecture/xCw8b/what-is-mobilization.

## What is "Mobilization"?

Within the mobilize phase, the focus is to create a migration plan and refine the business case. This is a great time to address gaps in the organization's readiness that were identified in the assess phase.

The migration strategy involves collecting application-portfolio data and rationalizing applications using the seven R's. AWS Application Service Service automatically collects and presents detailed information about application dependencies.

## Migration Plan Considerations

**Consideration #1**: It's crucial to ensure that everyone is in alignment on migration strategies, techniques, and plans.

One tool to help is the AWS Cloud Adoption Framework (CAF). This is a short book that leverages AWS experience and best practices to help you digitally transform and accelerate your business outcomes through the innovative use of AWS.

**Consideration #2**: It's important to consider how resources will translate to the AWS cloud.

In addition to considering **what** resources you're using in the on-premises environment, it's critical to consider **how** you're using them.

**Consideration #3**: Consider the pitfalls.

* Be cautious of actions or configurations that might introduce security and compliance risks during migration.
* Avoid hesitations around accepting new technologies, modernizations, and optimizations.
  * Modernization and optimization are an ongoing process.
* Ensure you have a clear business objective, and also a thought-out plan for migration and modernization.
  * It's easy to default to a lift-and-shift approach.

  ## What Needs to be Migrated?

  What are your servers doing? How many resources are they using? And how many other servers do they talk to?

  These questions can be answered with the **AWS Application Discovery Service**. It reports much of the configuration you'll need for migrating:
  * Servers MAC addresses
  * Average server CPU and RAM usage
  * Network traffic between servers, identifying dependencies

## Next

https://www.coursera.org/learn/aws-fundamentals-migrating-to-the-cloud/lecture/Wmn27/another-way-to-migrate