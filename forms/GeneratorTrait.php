<?php

namespace app\forms;

trait GeneratorTrait
{
	public static function generate($fields, $result)
	{
		$form = new static;
		$form->id = $result['id'];
		foreach ($fields as $key) {
			$form->$key = $result['form'][$key];
		}
		$form->attachments = static::mapAttachments($result['attachments']);
		return $form;
	}

	public static function mapAttachments($attachments)
	{
		return array_map(function($metadata) {
			return new Attachment($metadata);
		}, array_filter($attachments, function($metadata) {
			return $metadata['content_type'] != 'text/xml'; //form.xml
		}));
	}
}