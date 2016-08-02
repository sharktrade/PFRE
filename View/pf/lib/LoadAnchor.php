<?php 
/* $pfre: LoadAnchor.php,v 1.7 2016/07/31 14:19:13 soner Exp $ */

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

class LoadAnchor extends Rule
{
	function parse($str)
	{
		$this->str= $str;
		$this->deleteRules();
		$this->parseComment();
		
		// load anchor spam from "/etc/pf-spam.conf" # Comment
		// load anchor spam from /etc/pf-spam.conf # Comment
		if ((preg_match('/^\s*load\s+anchor\s+(\S+)\s+from\s+"([^"]+)"\s*$/', $this->str, $match)) ||
			(preg_match('/^\s*load\s+anchor\s+(\S+)\s+from\s+(\S+)\s*$/', $this->str, $match))) {
			$this->rule['anchor']= $match[1];
			$this->rule['file']= $match[2];
		}
	}

	function generate()
	{
		$this->str= 'load anchor ' . $this->rule['anchor'] . ' from "' . $this->rule['file'] . '"';
		
		$this->genComment();
		$this->str.= "\n";
		return $this->str;
	}
	
	function display($rulenumber, $count)
	{
		$this->dispHead($rulenumber);
		$this->dispAnchor();
		$this->dispTail($rulenumber, $count);
	}
	
	function dispAnchor()
	{
		?>
		<td title="Anchor">
			<?php echo $this->rule['anchor']; ?>
		</td>
		<td title="File" colspan="11">
			<?php echo $this->rule['file']; ?>
		</td>
		<?php
	}

	function input()
	{
		$this->inputKey('anchor');
		$this->inputKey('file');

		$this->inputKey('comment');
		$this->inputDelEmpty();
	}

	function edit($rulenumber, $modified, $testResult, $action)
	{
		$this->index= 0;
		$this->rulenumber= $rulenumber;

		$this->editHead($modified);

		$this->editText('anchor', 'Anchor', 'anchor-id', NULL, 'name, may be nested');
		$this->editText('file', 'File', FALSE, 40, 'filename');

		$this->editComment();
		$this->editTail($modified, $testResult, $action);
	}
}
?>