
/** ASSUMES USER ID=1 IS SET UP **/

insert into project (public_id, title_admin, created_at, owner_id) values ('test','Test','2016-01-01 00:00:00',1);
/* ID = 1 */

insert into field(project_id, public_id, created_at , title_admin , sort, label , description, type) values
(1, 'me_name', '2016-01-01 00:00:00', 'Me Name', 1, 'Your Name', 'What is your name?', 'TEXT');
/* ID = 1 */
insert into field(project_id, public_id, created_at , title_admin , sort, label , description, type) values
(1, 'me_address', '2016-01-01 00:00:00', 'Me Address', 1, 'Your Address', 'What is your address (inc postcode)?', 'TEXTAREA');
/* ID = 2 */

insert into project_version (project_id, public_id, created_at, title_admin, redirect_user_to_after_manual_stop) values
(1, 'v1', '2016-01-01 00:00:00', 'V1', 'http://google.com/');
/* ID = 1 */


insert into project_version_published (project_version_id, published_at) values (1, '2016-01-01 00:00:00');

insert into communication (created_at, project_version_id, sequence, title_admin, days_before, public_id,
email_content_text_template, email_content_html_template, email_subject_template) values
 ('2016-01-01 00:00:00',1,1,'First Communication', 0, 'comm_1',
 'email content text  {{ fields.me_name }}   {{ fields.me_address }} ',
 'email content html  {{ fields.me_name }}   {{ fields.me_address | nl2br }} ',
 'email subject');
/* ID = 1 */

insert into file (created_at, project_version_id, public_id, title_admin, filename, letter_content_template, `type`) VALUES
('2016-01-01 00:00:00', 1, 'lettertxt', 'Letter.Txt', 'letter.txt', 'Letter Content Here! Your welcome, {{ fields.me_name }} ', 'LETTER_TEXT' );
/* ID = 1 */

insert into communication_has_file (communication_id, file_id, created_at)
VALUES (1, 1, '2016-01-01 00:00:00');

insert into file (created_at, project_version_id, public_id, title_admin, filename, letter_content_template, `type`) VALUES
('2016-01-01 00:00:00', 1, 'lettermoretxt', 'LetterMore.Txt', 'letter_more.txt', 'Letter Content Here! For another letter.  Your welcome, {{ fields.me_name }} ', 'LETTER_TEXT');
/* ID = 2 */

insert into communication_has_file (communication_id, file_id, created_at)
VALUES (1, 2, '2016-01-01 00:00:00');

insert into file (created_at, project_version_id, public_id, title_admin, filename, letter_content_template, `type`) VALUES
('2016-01-01 00:00:00', 1, 'letterpdf', 'Letter.Pdf', 'letter.pdf', 'Letter Content Here! IN A PDF.\n\n\n Your welcome, {{ fields.me_name }} ', 'LETTER_PDF');
/* ID = 3 */

insert into communication_has_file (communication_id, file_id, created_at)
VALUES (1, 3, '2016-01-01 00:00:00');

insert into communication (created_at, project_version_id, sequence, title_admin, days_before, public_id,
email_content_text_template, email_content_html_template, email_subject_template) values
 ('2016-01-01 00:00:00',1,10,'Second Communication', 10, 'comm_2',
 'email content text  {{ fields.me_name }}   {{ fields.me_address }}      STOP: {{ stop_url }}',
 'email content html  {{ fields.me_name }}   {{ fields.me_address | nl2br  }}     STOP: {{ stop_url }} ',
 'email subject');
/* ID = 2 */



insert into access_point (created_at, project_version_id, public_id, title_admin, communication_id) VALUES
('2016-01-01 00:00:00', 1, 'start', 'Start', 1 );
/* ID = 1 */


insert into access_point_has_field (access_point_id, field_id, created_at) select 1, id, '2016-01-01 00:00:00' from field;

insert into access_point_has_file (access_point_id, file_id, created_at) select 1, id, '2016-01-01 00:00:00' from file;


insert into project_version_has_default_access_point(project_version_id, access_point_id, created_at) VALUES (1, 1, '2016-01-01 00:00:00');



