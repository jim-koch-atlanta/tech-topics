# Coursera: Migrating to the AWS Cloud: Migrate / Modernize

See https://www.coursera.org/learn/aws-fundamentals-migrating-to-the-cloud/lecture/Qb80k/migrating-data-part-1.

## AWS DataSync

DataSync is a secure online service that automates and accelerates moving data between on premises and AWS storage services. It works through an agent, running on a VM that is owned by you, to read or write data from your storage systems.

## AWS Storage Gateway

Storage Gateway delivers low-latency data access to on-premises applications. It provides on-premises applications access to cloud-backed storage without disruption to your systems and application workflows.

Storage Gateway utilizes a storage gateway application, which is a VM from AWS, installed and hosted within your data center.

## AWS Tape Gateway

Tape Gateway enables you to replace the use of physical tapes with virtual tapes in AWS. It doesn't require changing existing backup workflows.

## AWS Snow Family

The Snow Family was designed to solve the problem of moving your data to the cloud. You order one of the Snow devices, plug it into your data center, and then copy all of your information over to it. The data is encrypted, so it's completely secure.

You ship the Snow device to device to AWS, and AWS then moves that data into the S3 bucket that you've specified.

There are several Snow devices:
* The Snowcone
* The Snowball, which comes in two flavors:
  * The Snowball Edge Storage Optimized: 80 Tb of storage
  * The Snowball Edge Compute Optimized: 42 Tb of storage
* The Snowmobile: A semi truck of hard drives, for 100 Pb of storage.

## After Migration

Once migration is complete, we move to the operating phase. AWS provides many tools to support this effort. The first one is CloudFormation.

**CloudFormation** is an Infrastructure-as-Code solution. It dramatically simplifies deploying new architecture for your solution.

You can also put the templates into **Service Catalog**, and give users a self-service model when they want to deploy to a new Region or environment. Service Catalog gives the ability to easily deploy pre-packaged applications.

If you'd like some assistance, or even have someone do all of the heavy operational lifting for you, there's **AWS Managed Services**. AWS will manage setting up your architecture, building your network, and getting your applications up and running.

Lastly, you can continue to improve your AWS knowledge in **Skill Builder**, a platform with plenty of content to continue your cloud journey.

## Course Recap

* We should remember the **seven R's of migration**: relocate, rehost, replatform, repurchase, refactor, retain, and retire.

* A key step in the migration process is the **migration readiness assessment meeting**. During this, you create a strengths-and-weaknesses analysis to ensure that you follow all of the best practices when migrating. It's also where you make a plan to roll out training to the IT organization.

## Next

https://www.coursera.org/learn/architecting-solutions-on-aws/lecture/iYatj/course-introduction