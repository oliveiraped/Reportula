{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span12">
            <h3>{{ HTML::image('assets/img/pools.jpg') }} Pool - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
                 @unless ($Name=="")
                   | <a onclick='deleteitem("{{$config}}","{{$id}}" );' class='btn btn-small btn-danger'> Delete </a>
                @endunless
            </h3>
        </div>

    </div>
    <br>
    <div class="row-fluid">
        <div class="span6">
            {{Former::hidden('config')->id('config')->value($config);}}
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('PoolType', 'Pool Type')->placeholder('PoolType')->value($PoolType)->required();}}
            {{Former::text('VolumeUseDuration', 'Volume Use Duration')->placeholder('VolumeUseDuration')->value($VolumeUseDuration)->required();}}
            {{Former::text('AutoPrune', 'Auto Prune')->placeholder('AutoPrune')->value($AutoPrune)->required();}}
            {{Former::text('VolumeRetention', 'Volume Retention')->placeholder('VolumeRetention')->value($VolumeRetention)->required();}}
             {{Former::text('CleaningPrefix', 'Cleaning Prefix')->placeholder('CleaningPrefix')->value($CleaningPrefix);}}
            {{Former::text('LabelFormat', 'Label Format')->placeholder('LabelFormat')->value($LabelFormat)->required();}}
            {{Former::text('Recycle', 'Recycle')->placeholder('Recycle')->value($Recycle)->required();}}
            {{Former::text('RecycleOldestVolume', 'Recycle Oldest Volume')->placeholder('RecycleOldestVolume')->value($RecycleOldestVolume)->required();}}
            {{Former::text('RecycleCurrentVolume', 'Recycle Current Volume')->placeholder('RecycleCurrentVolume')->value($RecycleCurrentVolume);}}
            {{Former::text('RecyclePool', 'Recycle Pool')->placeholder('RecyclePool')->value($RecyclePool);}}
        </div>
        <div class="span6">
            {{Former::text('PurgeOldestVolume', 'Purge Oldest Volume')->placeholder('PurgeOldestVolume')->value($PurgeOldestVolume);}}
            {{Former::text('MaximumVolumes', 'Maximum Volumes')->placeholder('MaximumVolumes')->value($MaximumVolumes);}}
            {{Former::text('MaximumVolumeJobs', 'Max. Volume Jobs')->placeholder('MaximumVolumeJobs')->value($MaximumVolumeJobs);}}
            {{Former::text('MaximumVolumeFiles', 'Max. Volume Files')->placeholder('MaximumVolumeFiles')->value($MaximumVolumeFiles);}}
            {{Former::text('MaximumVolumeBytes', 'Max. Volume Bytes')->placeholder('MaximumVolumeBytes')->value($MaximumVolumeBytes);}}
            {{Former::text('ScratchPool', 'Scratch Pool')->placeholder('ScratchPool')->value($ScratchPool);}}
            {{Former::text('Storage', 'Storage')->placeholder('Storage')->value($Storage);}}
            {{Former::text('UseVolumeOnce', 'Use Volume Once')->placeholder('UseVolumeOnce')->value($UseVolumeOnce);}}
            {{Former::text('CatalogFiles', 'Catalog Files')->placeholder('CatalogFiles')->value($CatalogFiles);}}
            {{Former::text('FileRetention', 'File Retention')->placeholder('FileRetention')->value($FileRetention);}}
            {{Former::text('JobRetention', 'Job Retention')->placeholder('JobRetention')->value($JobRetention);}}
        </div>
    <div>
    {{Former::close();}}
    </div>
