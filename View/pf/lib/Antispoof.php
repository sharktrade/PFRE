<?php
/* $pfre: Antispoof.php,v 1.3 2016/07/31 14:19:13 soner Exp $ */

/*
 * Copyright (c) 2016 Soner Tari.  All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this
 *    software must display the following acknowledgement: This
 *    product includes software developed by Soner Tari
 *    and its contributors.
 * 4. Neither the name of Soner Tari nor the names of
 *    its contributors may be used to endorse or promote products
 *    derived from this software without specific prior written
 *    permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR
 * IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 * OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.
 * IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
 * NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
 * THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

class Antispoof extends Rule
{
	function __construct($str)
	{
		$this->keywords= array_merge(
			$this->keywords,
			array(
				'log' => array(
					'method' => 'parseLog',
					'params' => array(),
					),
				'quick' => array(
					'method' => 'parseBool',
					'params' => array(),
					),
				'for' => array(
					'method' => 'parseItems',
					'params' => array('interface'),
					),
				'inet' => array(
					'method' => 'parseNVP',
					'params' => array('af'),
					),
				'inet6' => array(
					'method' => 'parseNVP',
					'params' => array('af'),
					),
				'label' => array(
					'method' => 'parseDelimitedStr',
					'params' => array('label'),
					),
				)
			);

		parent::__construct($str);
	}

	function generate()
	{
		$this->str= 'antispoof';

		$this->genLog();
		$this->genKey('quick');
		$this->genItems('interface', 'for');
		$this->genValue('af');
		$this->genValue('label', 'label "', '"');

		$this->genComment();
		$this->str.= "\n";
		return $this->str;
	}
	
	function display($rulenumber, $count)
	{
		$this->dispHead($rulenumber);
		$this->dispValue('interface', 'Interface');
		$this->dispKey('quick', 'Quick');
		$this->dispValue('af', 'Address Family');
		$this->dispLog(8);
		$this->dispValue('label', 'Label');
		$this->dispTail($rulenumber, $count);
	}
	
	function input()
	{
		$this->inputLog();
		$this->inputBool('quick');

		$this->inputDel('interface', 'dropinterface');
		$this->inputAdd('interface', 'addinterface');
		$this->inputKey('af');
		$this->inputKey('label');

		$this->inputKey('comment');
		$this->inputDelEmpty();
	}

	function edit($rulenumber, $modified, $testResult, $action)
	{
		$this->index= 0;
		$this->rulenumber= $rulenumber;

		$this->editHead($modified);

		$this->editLog();
		$this->editCheckbox('quick', 'Quick');

		$this->editValues('interface', 'Interface', 'dropinterface', 'addinterface', 'if or macro', NULL, 10);
		$this->editAf();
		$this->editText('label', 'Label', NULL, NULL, 'string');

		$this->editComment();
		$this->editTail($modified, $testResult, $action);
	}
}
?>
