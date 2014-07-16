{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/pools.jpg') }} Pool - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}} </h3>
        </div>
        
    </div>
    <br>
    <div class="row-fluid">
        <div class="span6">
                        {{Former::hidden('config')->id('config')->value($config);}}

            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('PoolType', 'PoolType')->placeholder('PoolType')->value($PoolType);}}
            {{Former::text('Storage', 'Storage')->placeholder('Storage')->value($Storage);}}
            {{Former::text('UseVolumeOnce', 'Use Volume Once')->placeholder('UseVolumeOnce')->value($UseVolumeOnce);}}
            {{Former::text('VolumeUseDuration', 'Volume Use Duration')->placeholder('VolumeUseDuration')->value($VolumeUseDuration);}}
            {{Former::text('CatalogFiles', 'Catalog Files')->placeholder('CatalogFiles')->value($CatalogFiles);}}
            {{Former::text('AutoPrune', 'Auto Prune')->placeholder('AutoPrune')->value($AutoPrune);}}
            {{Former::text('VolumeRetention', 'Volume Retention')->placeholder('VolumeRetention')->value($VolumeRetention);}}
            {{Former::text('FileRetention', 'File Retention')->placeholder('FileRetention')->value($FileRetention);}}
            {{Former::text('JobRetention', 'Job Retention')->placeholder('JobRetention')->value($JobRetention);}}
            {{Former::text('CleaningPrefix', 'Cleaning Prefix')->placeholder('CleaningPrefix')->value($CleaningPrefix);}}
            {{Former::text('LabelFormat', 'Label Format')->placeholder('LabelFormat')->value($LabelFormat);}}

        </div>
        <div class="span6">
            {{Former::text('MaximumVolumes', 'Maximum Volumes')->placeholder('MaximumVolumes')->value($MaximumVolumes);}}
            {{Former::text('MaximumVolumeJobs', 'Max. Volume Jobs')->placeholder('MaximumVolumeJobs')->value($MaximumVolumeJobs);}}
            {{Former::text('MaximumVolumeFiles', 'Max. Volume Files')->placeholder('MaximumVolumeFiles')->value($MaximumVolumeFiles);}}
            {{Former::text('MaximumVolumeBytes', 'Max. Volume Bytes')->placeholder('MaximumVolumeBytes')->value($MaximumVolumeBytes);}}
            {{Former::text('RecyclePool', 'Recycle Pool')->placeholder('RecyclePool')->value($RecyclePool);}}
            {{Former::text('RecycleOldestVolume', 'Recycle Oldest Volume')->placeholder('RecycleOldestVolume')->value($RecycleOldestVolume);}}
            {{Former::text('RecycleCurrentVolume', 'Recycle Current Volume')->placeholder('RecycleCurrentVolume')->value($RecycleCurrentVolume);}}
            {{Former::text('Recycle', 'Recycle')->placeholder('Recycle')->value($Recycle);}}
            {{Former::text('PurgeOldestVolume', 'Purge Oldest Volume')->placeholder('PurgeOldestVolume')->value($PurgeOldestVolume);}}
            {{Former::text('ScratchPool', 'Scratch Pool')->placeholder('ScratchPool')->value($ScratchPool);}}
        </div>
    <div>
    {{Former::close();}}
    </div>  
