<?php
namespace Craft;

class SproutReportsSproutFormsIntegration extends SproutReportsBaseReport
{
	protected $name;
	protected $query;
	protected $group = 'Sprout Forms';
	protected $userOptions = array();
	protected $description = '';
	protected $isCustomQueryEditable = false;

	/*
	* Set report group
	* @param $group string
    * @return void
	*/
	public function setGroup($group)
	{
		$this->group = $group;
	}

	/*
	* Report group
    * @return string
	*/
	public function getGroup()
	{
		return $this->group;
	}

	/*
	 * Set report name
	 * @param $name string
	 * @return void
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/*
	 * Report name
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/*
	 * Set report description
	 * @param $name string
	 * @return void
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/*
	 * Report description
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/*
	 * Set SQL query to be used for generating reports
	 * @param $query string
	 * @return void
	 */
	public function setQuery($query)
	{
		$this->query = $query;
	}

	/*
	 * SQL query to be used for generating reports
	 * @return string
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/*
	 * Set user options for report
	 * @return void
	 */
	public function setUserOptions(array $userOptions)
	{
		$this->userOptions = $userOptions;
	}

	/*
	 * User options for report
	 * @return array
	 */
	public function getUserOptions()
	{
		return $this->userOptions;
	}

    /*
     * Set is custom query editable or not
     * @return void
     */
    public function setIsCustomQueryEditable($isEditable)
    {
        $this->isCustomQueryEditable = $isEditable;
    }

    /*
     * Is custom query editable or not
     * @return boolean
     */
    public function getIsCustomQueryEditable()
    {
        return $this->isCustomQueryEditable;
    }

	/*
	 * Create ReportBase from SproutForm object
	 * @param SproutForms
	 */

	public static function createReport($form)
	{
		$report = new SproutReportsSproutFormsIntegration();
		$report->setName($form->name);
		$report->setQuery('SELECT * FROM {{sproutformscontent_'.$form->handle.'}}');
        $report->setIsCustomQueryEditable(false);
        $userOptions = array();
        $userOptions['dateCreatedFrom'] = array(
            'type' => 'date',
            'column' => 'dateCreated',
            'name' => 'Creation date: from',
            'comparisonOperator' => '>=',
            'showDate' => true,
            'showTime' =>  true,
            'defaultValue' => array(
                'isSQL' => true,
                'value' => 'SELECT MIN(dateCreated) FROM {{sproutformscontent_'.$form->handle.'}}'
            )
        );
        $userOptions['dateCreatedTill'] = array(
            'type' => 'date',
            'column' => 'dateCreated',
            'name' => 'Creation date: till',
            'comparisonOperator' => '<=',
            'showDate' => true,
            'showTime' =>  true,
            'defaultValue' => array(
                'isSQL' => true,
                'value' => 'SELECT MAX(dateCreated) FROM {{sproutformscontent_'.$form->handle.'}}'
            )
        );
        $formFields = $form->getFieldLayout()->getFields();
        foreach ($formFields as $field)
        {
            $field = $field->getField();
            switch ($field->type)
            {
                case 'Dropdown':
                case 'Checkboxes':
                case 'RadioButtons':
                    $fieldOptions = array();
                    $default = false;
                    foreach ($field['settings']['options'] as $option)
                    {
                        if ($option['value'])
                        {
                            $fieldOptions[] = array(
                                'label' => $option['label'],
                                'value' => $option['value']
                            );
                        }
                        if ($option['default'])
                        {
                            $default = $option['value'];
                        }
                    }

                    if (count($fieldOptions))
                    {
                        $userOptions['field_'.$field['handle']] = array(
                            'type' => strtolower($field->type),
                            'column' => 'field_'.$field['handle'],
                            'name' => $field['name'],
                            'comparisonOperator' => ($field->type == 'Checkboxes') ? 'LIKE' : '=',
                            'values' => $fieldOptions,
                            'defaultValue' => array(
                                'isSQL' => false,
                                'value' => $default
                            )
                        );
                    }
                    break;
            }
        }

        $report->setUserOptions($userOptions);
		return $report;
	}

    /*
     * Prepare query params for using in sql query, they override query params in SproutReports plugin
     * @var $params array
     * @return array
     */
    public static function prepareQueryParams($params)
    {
        $processedParams = array();
        return $processedParams;
    }
}