<!-- Main hero unit for a primary marketing message or call to action -->
<div class="hero-unit">
	<h2>Insert</h2>
	<pre>
	APP.'setup'ディレクトリ以下のModelName.ymlをインサートします
	</pre>
	<?php if(!$files):?>
	<p>対象ファイルがありません</p>
	<?php endif;?>
</div>

<div class="row">
	<?php echo $this->Form->create(false,array('action'=>'run'));?>
	<div class="offset4">
	<?php
			echo $this->Form->input('select_file',array(
					'multiple'=>'checkbox',
					'options'=>$files,
					'label'=>false,
			));
	?>
	</div>
	<hr />
	<div class="offset4">
	<?php echo $this->Form->submit('実行！',array('action'=>'run'),array('class'=>'btn'));?>
	</div>
	<?php echo $this->Form->end();?>	
</div>