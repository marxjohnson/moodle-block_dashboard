<?php

class block_dashboard_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_dashboard'));
        $mform->setType('config_title', PARAM_MULTILANG);

        $editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'noclean'=>true, 'context'=>$this->block->context);
        $mform->addElement('editor', 'config_whatshot', get_string('whatshot', 'block_dashboard'), null, $editoroptions);
        $mform->setType('config_whatshot', PARAM_RAW); // XSS is prevented when printing the block contents and serving files
    }

    function set_data($defaults) {
        if (!empty($this->block->config) && is_object($this->block->config)) {
            $whatshot = $this->block->config->whatshot;
            $draftid_editor = file_get_submitted_draft_itemid('config_whatshot');
            if (empty($whatshot)) {
                $currenttext = '';
            } else {
                $currenttext = $whatshot;
            }
            $defaults->config_whatshot['text'] = file_prepare_draft_area($draftid_editor, $this->block->context->id, 'block_dashboard', 'content', 0, array('subdirs'=>true), $currenttext);
            $defaults->config_whatshot['itemid'] = $draftid_editor;
            $defaults->config_whatshot['format'] = $this->block->config->format;
        } else {
            $whatshot = '';
        }

        if (!$this->block->user_can_edit() && !empty($this->block->config->title)) {
            // If a title has been set but the user cannot edit it format it nicely
            $title = $this->block->config->title;
            $defaults->config_title = format_string($title, true, $this->page->context);
            // Remove the title from the config so that parent::set_data doesn't set it.
            unset($this->block->config->title);
        }

        // have to delete text here, otherwise parent::set_data will empty content
        // of editor
        unset($this->block->config->whatshot);
        parent::set_data($defaults);
        // restore $text
        $this->block->config->whatshot = $whatshot;
        if (isset($title)) {
            // Reset the preserved title
            $this->block->config->title = $title;
        }
    }
}
