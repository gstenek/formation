<?php
/**
 * Created by PhpStorm.
 * User: gstenek
 * Date: 01/03/2017
 * Time: 09:31
 */

namespace OCFram;

class FormHandler
{
	protected $form;
	protected $manager;
	protected $request;
	
	public function __construct(Form $form, Manager $manager, HTTPRequest $request)
	{
		$this->setForm($form);
		$this->setManager($manager);
		$this->setRequest($request);
	}
	
	public function process()
	{
		if($this->request->method() == 'POST' && $this->form->isValid())
		{
			$this->manager->save($this->form->entity());
			
			return true;
		}
		foreach ( $this->form->Fields() as $field ) {
			
			if ( $field->type() == 'password' ) { // s'il s'agit d'un password, on assigne sa valeur à vide
				$field->setValue( '' );
			}
		}
		return false;
	}
	
	public function setForm(Form $form)
	{
		$this->form = $form;
	}
	
	public function setManager(Manager $manager)
	{
		$this->manager = $manager;
	}
	
	public function setRequest(HTTPRequest $request)
	{
		$this->request = $request;
	}
}