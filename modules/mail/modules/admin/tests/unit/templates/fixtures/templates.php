<?php
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */
return [
    'uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'mail-template-' . $index)->toString(),
    'code' => 'MAIL_TEMPLATE_' . $index,
    'active' => true,
    'from' => $faker->unique()->email,
    'to' => $faker->unique()->email,
    'replyTo' => $faker->unique()->email,
    'bcc' => $faker->unique()->email,
    'subject' => $faker->text(),
    'textBody' => $faker->text(),
    'htmlBody' => $faker->randomHtml(),
    'workflow_uuid' => \Ramsey\Uuid\Uuid::uuid3(\Ramsey\Uuid\Uuid::NAMESPACE_URL, 'workflow-' . $index)->toString()
];