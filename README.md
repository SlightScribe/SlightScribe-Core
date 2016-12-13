# SlightScribe



## What is it?

This is a Symfony app that lets you run a letter writing tool. Users are asked for several fields of data about their situation, and then can be given customised files for download immediately from the web or by email. They can be sent an email later with other customised files, to walk them through a process.


## Projects

This app can host multiple projects. Each project has it's own set of data and configuration options.

## A Project Run

A Project Run is one user, going through a project.

They start by filling in a form with details.

After that they might be able to download files or they might be sent emails. They may be sent more than one email, with a gap between them.

They can visit a URL during the process to stop the process half way through. On successfully stopping a process, the user is redirected to a URL defined on the Project Version (redirect_user_to_after_manual_stop column on project_version table).

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

Fields can be of types:

 * Text - single line Text.
 * TextArea - multi line Text.
 * Date - a date.

Each field has a Public Id, Or Key. This should be alphanumeric only and this will be referenced by templates later.

These are tied to a project rather than a project version so that when a project is upgraded from a old version to a new version, existing data can be mapped across in some way. There aren't yet tools to do that, but that is the intention.

## Access Points

At the moment, each Project Version only has one Access Point, and this Access Point is the default Access Point that a user starts by accessing when they start a process.

The intention is that in the future, you will be able to define other points and the user will be directed back to them. Eg

  * User starts by filling out the default access point.
  * User gets first Communication/email.
  * A week later, User gets second Communication/email. This contains a link to continue.
  * This link opens a webpage containing a form, fields and info defined by a second access point.
  * After this, third Communication/email is sent.

This allows the user to be checked so we can make sure details given are still correct and have not changed since the start of the process.

### Access points have fields

It can defined which fields will be set on an access point.

### Access points have a form

The form is shown to the user.

For each field on this access point, the placeholder {{FIELD_NAME}} will be replaced with the actual form element.

### Access points have files

When the access point is completed, the user can be shown links to download files straight away.

## File

A file can be given to the user either by attaching to an email, or by making available through an access point.

A file defines what type of file it is, currently options are:

  *  Text
  *  PDF

A file also contains templates. These templates are rendered to provide the content in the file. There are several templates:

  *  Letter Content Template Header Right - if given, this will be at the top of the first page only and will be indented so it appears on the right.
  *  Letter Content Template - main content

The template has the following variables made available to it:

  *  fields. Access by field name, eg ```{{ fields.field_name }}```
  *  previousCommunications - the date of each one. eg ```{{ previousCommunications[communication_public_id].created_at }}```
  *  projectRun. From here you can access ```{{ projectRun.email }}``` and ```{{ projectRun.createdAt }}```

The template is [a Twig template](http://twig.sensiolabs.org/) and thus options available in twig (eg If statements) are also available here. It is possible to introduce errors writing Twig templates, so the Preview feature should always be used to test them.

## Communication

A communication is an email sent to a user.

Each communication has:

  *  A sequence number, to order them. Lower numbers are sent first.
  *  A days before field, to set how much of a gap to leave between emails.
  *  A list of files which are attached to the email.
  *  A subject template.
  *  A body Text email template.
  *  A body HTML email template. This is optional.

The template has the following variables made available to it:

  *  fields. Access by field name, eg ```{{ fields.field_name }}```
  *  previousCommunications - the date of each one. eg ```{{ previousCommunications[communication_public_id].created_at }}```
  *  projectRun. From here you can access ```{{ projectRun.email }}``` and ```{{ projectRun.createdAt }}```
  *  stop_url - A URL of a webpage. Here, the user can stop the process and they won't receive any further emails.

The template is [a Twig template](http://twig.sensiolabs.org/) and thus options available in twig (eg If statements) are also available here. It is possible to introduce errors writing Twig templates, so the Preview feature should always be used to test them.

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
