# SlightScribe



## What is it?

This is a Symfony app that lets you run a letter writing tool. Users are asked for several fields of data about their situation, and then can be given customised files for download immediately from the web or by email. They can be sent an email later with other customised files, to walk them through a process.


## Projects

This app can host multiple projects. Each project has it's own set of data and configuration options.

## Project Versions

Each project has multiple versions.

When looking at a project version, there is an option to make a new version from this - this will copy all existing data ready for editing.

Only one version can be published at once.

In this way, admin's can work on a new version of a project and the live version will be the same. When the new version is
ready and has been tested then the new version can be published.

Some bits of the published project Version can not be edited through the admin interface. This is to safeguard the live project from breakages.

## Blocked IP's and Email addresses

Email addresses and IP's are recorded. If one of them comes up to much, they are blocked from creating new runs.

These blocks apply across all projects on one app.

The limits can be configured in app/config/parameters.yml

  *  anti_spam_all_projects_block_ip_after_attempts
  *  anti_spam_all_projects_block_ip_after_attempts_in_seconds
  *  anti_spam_all_projects_block_email_after_attempts
  *  anti_spam_all_projects_block_email_after_attempts_in_seconds

## Fields

Each project has fields you can configure.

These are tied to a project rather than a project version so that TODO

## How to give admin access to a user

Get the user to register in the browser at /register

In the command line, run

    php app/console fos:user:promote

Enter the new users name and for a role enter: ROLE_ADMIN

The user will have to log out and in again to see the difference.

## Vagrant for development

Use Vagrant and Virtual Box for development

### Seeing app

```
vagrant up
```

The app will be available on http://localhost:8080/app_dev.php
