<!-- indexer::stop -->
<div class="mod_dms <?php echo $this->class; ?>"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="POST" enctype="multipart/form-data" id="management" name="management">
	<input type="hidden" name="FORM_SUBMIT" value="<?php echo $this->formId; ?>">
	<input type="hidden" name="REQUEST_TOKEN" value="{{request_token}}">
	
	<?php if (count($this->messages['errors']) > 0): ?>
	<!-- Errors -->
	<div id="dms_errors">
		<?php foreach ($this->messages['errors'] as $error): ?>
		<div class="error"><?php echo $error; ?></div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
		
	<?php if (count($this->messages['warnings']) > 0): ?>
	<!-- Warnings -->
	<div id="dms_warnings">
		<?php foreach ($this->messages['warnings'] as $warning): ?>
		<div class="warning"><?php echo $warning; ?></div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	
	<?php if (count($this->messages['successes']) > 0): ?>
	<!-- Successes -->
	<div id="dms_successes">
		<?php foreach ($this->messages['successes'] as $success): ?>
		<div class="success"><?php echo $success; ?></div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	
	<?php if (count($this->messages['infos']) > 0): ?>
	<!-- Infos -->
	<div id="dms_infos">
		<?php foreach ($this->messages['infos'] as $info): ?>
		<div class="info"><?php echo $info; ?></div>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	
	<table cellpadding="0" cellspacing="0" border="0">
		<thead>
			<tr>
				<th colspan="2" class="headline"><?php echo sprintf($GLOBALS['TL_LANG']['DMS']['management_headline'], $GLOBALS['TL_LANG']['DMS']['management_upload_headline'], $GLOBALS['TL_LANG']['DMS']['management_upload_processed_headline']); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_category']; ?></td>
				<td>
					<?php echo $this->category->name; ?>
					<?php $arrPathNames = $this->category->getPathNames(true); ?>
					<?php if (count($arrPathNames) > 0): ?>
						(<?php echo implode($GLOBALS['TL_LANG']['DMS']['management_path_separator'], $arrPathNames); ?>)
					<?php endif; ?>
				</td>
			</tr>
			<tr class="subheadline">
				<td class="label" colspan="2"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_file_headline']; ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_file_name']; ?></td>
				<td><?php echo $this->fileName; ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_file_type']; ?></td>
				<td><?php echo $this->fileType; ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_file_size']; ?></td>
				<td><?php echo $this->fileSizeMbFormatted; ?></td>
			</tr>
	<?php if (count($this->existingDocuments) > 0) : ?>
			<tr class="subheadline">
				<td class="label" colspan="2"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_headline']; ?></td>
			</tr>
		<?php foreach ($this->existingDocuments as $document) : ?>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_name']; ?></td>
				<td>
					<?php
						$title = sprintf($GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_size'], $document->getFileSize(Document::FILE_SIZE_UNIT_MB, true));
						if (strlen($document->getUploadDate() > 0) && $document->hasUploadMemberName())
						{
							$title .= "\n" . sprintf($GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_uploaded'], $document->getUploadDate(), $document->uploadMemberName);
						}
						if (strlen($document->getLasteditDate() > 0) && $document->hasLasteditMemberName())
						{
							$title .= "\n" . sprintf($GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_lastedited'], $document->getLasteditDate(), $document->lasteditMemberName);
						}
						$arrPathNames = $document->category->getPathNames(true);
						$categoryPath = "";
						if (count($arrPathNames) > 0)
						{
							$categoryPath = "(" . implode($GLOBALS['TL_LANG']['DMS']['management_path_separator'], $arrPathNames) . ")";
						}
						$title .= "\n" . sprintf($GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_category'], $document->category->name, $categoryPath);
					?> 
					<span class="existingDocument <?php if ($document->isPublished()) : ?>published<?php else: ?>unpublished<?php endif; ?>">
						<?php echo $document->name; ?> <?php echo sprintf($GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_version'], $document->getVersion()); ?> 
						- <?php if ($document->isPublished()) : ?><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_published']; ?><?php else: ?><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_existing_unpublished']; ?><?php endif; ?>
						<span class="explanation" title="<?php echo $title; ?>"><?php echo $GLOBALS['TL_LANG']['DMS']['management_explanation']; ?></span>
					</span>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
			<tr class="subheadline">
				<td class="label" colspan="2"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_document_headline']; ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_document_name']; ?></td>
				<td><?php echo $this->document->name; ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_document_description']; ?></td>
				<td><?php echo $this->document->description; ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_document_keywords']; ?></td>
				<td><?php echo $this->document->keywords; ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_document_version']; ?></td>
				<td><?php echo $this->document->getVersion(); ?></td>
			</tr>
			<tr>
				<td class="label"><?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_properties_document_publish']; ?></td>
				<td>
				<?php if ($this->document->isPublished()) : ?>
					<?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_processed_published']; ?>
				<?php else : ?>
					<?php echo $GLOBALS['TL_LANG']['DMS']['management_upload_processed_unpublished']; ?>
				<?php endif; ?>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">
					<button type="submit" name="abort" value="true"><?php echo $GLOBALS['TL_LANG']['DMS']['management_button_back']; ?></button>
					<button type="submit" name="uploadCategory" value="<?php echo $this->category->id; ?>"><?php echo $GLOBALS['TL_LANG']['DMS']['management_button_upload_another']; ?></button>
				</td>
			</tr>
		</tfoot>
	</table>
</form>

</div>
<!-- indexer::continue --> 