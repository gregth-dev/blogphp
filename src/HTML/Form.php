<?php

namespace App\HTML;

use DateTimeInterface;

class Form
{

    protected $data;
    protected $errors;

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function input(string $key, string $label): string
    {
        $type = $key === 'password' ? 'password' : 'text';
        $value = $this->getValue($key);
        [$inputClass, $invalidFeedback] = $this->getInputFields($key);
        return <<<HTML
            <div class="form-group">
                <label for="field{$key}">{$label} :</label>
                <input type="{$type}" id="field{$key}" class="{$inputClass}" name="{$key}" value="{$value}" 
                >
                {$invalidFeedback}
            </div>
HTML;
    }

    public function chekbox(string $key, string $label): string
    {
        return <<<HTML
        <div class="form-group form-check">
        <input type="checkbox" name="{$key}" id="{$key}">
            <label class="form-check-label" for="{$key}">{$label}</label>
        </div>           
HTML;
    }

    public function file(string $key, string $label): string
    {
        $invalidFeedback = $this->getInputFieldsFile($key);
        return <<<HTML
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="field{$key}" name="{$key}">
                <label class="custom-file-label" for="field{$key}">{$label} :</label>
            </div>
        </div>
        {$invalidFeedback}  
HTML;
    }

    public function textarea(string $key, string $label): string
    {
        $value = $this->getValue($key);
        [$inputClass, $invalidFeedback] = $this->getInputFields($key);
        return <<<HTML
            <div class="form-group">
                <label for="field{$key}">{$label} :</label>
                <textarea type="text" id="field{$key}" class="{$inputClass}" name="{$key}" cols="30" rows="8" required>{$value}</textarea>
                {$invalidFeedback}
            </div>
HTML;
    }


    private function getValue(string $key)
    {
        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }
        $method = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
        $value = $this->data->$method();
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
    }


    private function getInputFieldsFile($key)
    {

        $invalidFeedback = "";
        if (isset($this->errors[$key])) {
            if (is_array($this->errors[$key])) {
                $error = implode('<br>', $this->errors[$key]);
            } else {
                $error = $this->errors[$key];
            }

            $invalidFeedback = '<div class="alert alert-danger">' . $error . '</div>';
        }
        return $invalidFeedback;
    }

    private function getInputFields($key)
    {
        $inputClass = 'form-control';
        $invalidFeedback = "";
        if (isset($this->errors[$key])) {
            if (is_array($this->errors[$key])) {
                $error = implode('<br>', $this->errors[$key]);
            } else {
                $error = $this->errors[$key];
            }
            $inputClass .= ' is-invalid';
            $invalidFeedback = '<div class="invalid-feedback">' . $error . '</div>';
        }
        return [$inputClass, $invalidFeedback];
    }

    public function select(string $key, string $label, array $options = []): string
    {
        $optionsHTML = [];
        $value = $this->getValue($key);
        foreach ($options as $k => $v) {
            $selected = in_array($k, $value) ? "selected" : "";
            $optionsHTML[] = "<option $selected value=\"$k\">$v</option>";
        }
        $optionsHTML = implode('', $optionsHTML);
        [$inputClass, $invalidFeedback] = $this->getInputFields($key);
        return <<<HTML
            <div class="form-group">
                <label for="field{$key}">{$label} :</label>
                <select id="field{$key}" class="{$inputClass}" name="{$key}[]" required multiple>{$optionsHTML}</select>
                {$invalidFeedback}
            </div>
HTML;
    }
}
