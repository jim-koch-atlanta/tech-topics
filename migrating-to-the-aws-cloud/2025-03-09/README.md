# Coursera: Migrating to the AWS Cloud: Migrate / Modernize

See https://www.coursera.org/learn/aws-fundamentals-migrating-to-the-cloud/lecture/q07wa/what-is-migrate-and-modernize.

## Migrating Application and Servers

The **AWS Application Migration Service** is all about giving you data to help make decisions. It provides an agent that can be installed onto your servers directly, or it provides agentless replication for VMware systems.

It picks up the OS, applications, data, and all of your applications, and it creates the architecture in AWS. The data is encrypted in transit, and it can be easily configured to also use encrypted EBS volumes.

AWS also supports Windows workloads. In fact, AWS has the **Migration Acceleration Program for Windows** to assess, mobilize, and then migrate Windows solutions.

## Migrating Databases

The AWS Database Migration Service (AWS DMS) helps you when migrating or transforming databases. It can help you migration to and from most of the widely used commercial and open source databases.

Heterogeneous migrations are a two-step process. First, use the AWS Schema Conversion Tool (AWS SCT) to convert the source schema and code to match that of the target database. SCT automatically converts the source database schema and a majority of the database code objects, including views, stored procedures, and functions, to a format compatible with the target database. Any objects that cannot be automatically converted are clearly marked, so they can be manually converted to complete the migration.

Second, use DMS to migrate data from the source database to the target. All required data types will be automatically converted during the migration.

You can use AWS DMS to consolidate multiple source databases into a single target database.

## Next

https://www.coursera.org/learn/aws-fundamentals-migrating-to-the-cloud/lecture/Qb80k/migrating-data-part-1