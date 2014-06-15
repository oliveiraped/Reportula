<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span4">
                <h3>{{ HTML::image('assets/img/user.png') }} Pool - {{ $Name }}</h3>
        </div>
        <div class="span2 pull-right">
                {{Form::submit( ' Save ', array('class' => 'btn btn-large btn-primary' ));}}
                 <a href="{{ URL::route('admin.users') }}" class="btn btn-large">
                    <i class="icon-fam-cross"></i>Close
                </a>
        </div>
    </div>
    <div class="row-fluid">

    {{ Former::horizontal_open('configurator/savedirector','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
        <div class="span6">
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('PoolType', 'PoolType')->placeholder('PoolType');}}
            {{Former::text('Storage', 'Storage')->placeholder('Storage');}}
            {{Former::text('UseVolumeOnce', 'Use Volume Once')->placeholder('UseVolumeOnce');}}
            {{Former::text('VolumeUseDuration', 'Volume Use Duration')->placeholder('VolumeUseDuration');}}
            {{Former::text('CatalogFiles', 'Catalog Files')->placeholder('CatalogFiles');}}
            {{Former::text('AutoPrune', 'Auto Prune')->placeholder('AutoPrune');}}
            {{Former::text('VolumeRetention', 'Volume Retention')->placeholder('VolumeRetention');}}
            {{Former::text('FileRetention', 'File Retention')->placeholder('FileRetention');}}
            {{Former::text('JobRetention', 'Job Retention')->placeholder('JobRetention');}}
            {{Former::text('CleaningPrefix', 'Cleaning Prefix')->placeholder('CleaningPrefix');}}
            {{Former::text('LabelFormat', 'Label Format')->placeholder('LabelFormat');}}

        </div>
        <div class="span6">
            {{Former::text('MaximumVolumes', 'Maximum Volumes')->placeholder('MaximumVolumes');}}
            {{Former::text('MaximumVolumeJobs', 'Max. Volume Jobs')->placeholder('MaximumVolumeJobs');}}
            {{Former::text('MaximumVolumeFiles', 'Max. Volume Files')->placeholder('MaximumVolumeFiles');}}
            {{Former::text('MaximumVolumeBytes', 'Max. Volume Bytes')->placeholder('MaximumVolumeBytes');}}
            {{Former::text('RecyclePool', 'Recycle Pool')->placeholder('RecyclePool');}}
            {{Former::text('RecycleOldestVolume', 'Recycle Oldest Volume')->placeholder('RecycleOldestVolume');}}
            {{Former::text('RecycleCurrentVolume', 'Recycle Current Volume')->placeholder('RecycleCurrentVolume');}}
            {{Former::text('Recycle', 'Recycle')->placeholder('Recycle');}}
            {{Former::text('PurgeOldestVolume', 'Purge Oldest Volume')->placeholder('PurgeOldestVolume');}}
            {{Former::text('ScratchPool', 'Scratch Pool')->placeholder('ScratchPool');}}
        </div>
    <div>
    {{Former::close();}}
    </div>  
