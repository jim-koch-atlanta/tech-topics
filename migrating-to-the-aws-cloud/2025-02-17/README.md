# Coursera: Migrating to the AWS Cloud: Assessing Review

See https://www.coursera.org/learn/aws-fundamentals-migrating-to-the-cloud/lecture/Q95VF/assessing-review.

## Assessing Review

As a recap:

* A migration readiness assessment (MRA) can help map out the organization's strengths and weaknesses.
* AWS provides many tools to help with migration, including the Migration Evaluator.
* Keep in mind the 7 R's of migration (Relocate, Rehost, Replatform, Repurchase, Refactor, Retire, Retain).
* The Migration Hub is broken up into five sections, each helping with a different stage of the migration process. These are Discover, Assess, Strategy, Orchestrate, and Integrate.
  * Discover helps you figure out what sort of resources you've got running in your on-prem world.
  * Assess can give you recommendations on the types of EC2 instances you should be using.
  * Strategy assists with recommendations on modernizing your applications for the cloud.
  * Orchestrate can give you a plan to follow, and steps for who does what.
  * Migrate integrates with the Application Migration Service and the Database Migration Service.

## AWS Mainframe Modernaization Service

The AWS Maintenance Modernization Service helps organizations to move mainframe workloads off of old computers and into the cloud.

The Assess stage gives insights into proposed cahnges for older applications. One of the nex steps is to refactor the code. You have a few options:
* Go through the manual process of fixing it all up.
* Automatically transform the code into agile Java services and web frameworks.
* Re-platform the existing code to run directly on AWS EC2 instances.

## SAP Workloads

There are over 130 different instance types that are certified by SAP to work. If you'd like to migrate your existing workloads, AWS offers a highly-automated lift-and-shift solution.

The AWS Backup AGent backs up your SAP HANA database to S3 and then restores it later.

## Next

https://www.coursera.org/learn/aws-fundamentals-migrating-to-the-cloud/lecture/xCw8b/what-is-mobilization