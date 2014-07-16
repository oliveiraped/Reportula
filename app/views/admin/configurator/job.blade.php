 {{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
         <h3>{{ HTML::image('assets/img/jobs.jpg') }} Job - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}} </h3>
    </div>
    <br>
    <div class="row-fluid">
        <div class="span6">
                        {{Former::hidden('config')->id('config')->value($config);}}

            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('Enabled', 'Enabled')->placeholder('Enabled')->value($Enabled);}}
            {{Former::text('Type', 'Type')->placeholder('Type')->value($Type);}}
            {{Former::text('Level', 'Level')->placeholder('Level')->value($Level);}}
            {{Former::text('Accurate', 'Accurate')->placeholder('Accurate')->value($Accurate);}}
            {{Former::text('VerifyJob', 'Verify Job')->placeholder('VerifyJob')->value($VerifyJob);}}
            {{Former::text('JobDefs', 'Job Defs')->placeholder('JobDefs')->value($JobDefs);}}
            {{Former::text('Bootstrap', 'Bootstrap')->placeholder('Bootstrap')->value($Bootstrap);}}
            {{Former::text('WriteBootstrap', 'Write Bootstrap')->placeholder('WriteBootstrap')->value($WriteBootstrap);}}
            {{Former::text('Client', 'Client')->placeholder('Client')->value($Client);}}
            {{Former::text('MaxRunTime', 'MaxRunTime')->placeholder('MaxRunTime')->value($MaxRunTime);}}
            {{Former::text('DifferentialMaxWaitTime', 'DifferentialMaxWaitTime')->placeholder('DifferentialMaxWaitTime')->value($DifferentialMaxWaitTime);}}
            {{Former::text('MaxRunSchedTime', 'MaxRunSchedTime')->placeholder('MaxRunSchedTime')->value($MaxRunSchedTime);}}
            {{Former::text('MaxWaitTime', 'MaxWaitTime')->placeholder('MaxWaitTime')->value($MaxWaitTime);}}
            {{Former::text('MaxStartDelay', 'MaxStartDelay')->placeholder('MaxStartDelay')->value($MaxStartDelay);}}
            {{Former::text('PruneJobs', 'PruneJobs')->placeholder('PruneJobs')->value($PruneJobs);}}
            {{Former::text('PreferMountedVolumes', 'PreferMountedVolumes')->placeholder('PreferMountedVolumes')->value($PreferMountedVolumes);}}
        </div>
        <div class="span6">
            {{Former::text('FileSet', 'FileSet')->placeholder('FileSet')->value($FileSet);}}
            {{Former::text('Base', 'Base')->placeholder('Base')->value($Base);}}
            {{Former::text('Messages', 'Messages')->placeholder('Messages')->value($Messages);}}
            {{Former::text('Pool', 'Pool')->placeholder('Pool')->value($Pool);}}
            {{Former::text('FullBackupPool', 'FullBackupPool')->placeholder('FullBackupPool')->value($FullBackupPool);}}
            {{Former::text('MaximumBandwidth', 'MaximumBandwidth')->placeholder('MaximumBandwidth')->value($MaximumBandwidth);}}
            {{Former::text('IncrementalBackupPool', 'IncrementalBackupPool')->placeholder('IncrementalBackupPool')->value($IncrementalBackupPool);}}
            {{Former::text('Storage', 'Storage')->placeholder('Storage')->value($Storage);}}
            {{Former::text('DifferentialBackupPool', 'DifferentialBackupPool')->placeholder('DifferentialBackupPool')->value($DifferentialBackupPool);}}
            {{Former::text('Schedule', 'Schedule')->placeholder('Schedule')->value($Schedule);}}
            {{Former::text('IncrementalMaxRunTime', 'IncrementalMaxRunTime')->placeholder('IncrementalMaxRunTime')->value($IncrementalMaxRunTime);}}
            {{Former::text('PruneVolumes', 'PruneVolumes')->placeholder('PruneVolumes')->value($PruneVolumes);}}
            {{Former::text('SpoolData', 'SpoolData')->placeholder('SpoolData')->value($SpoolData);}}
            {{Former::text('RunBeforeJob', 'RunBeforeJob')->placeholder('RunBeforeJob')->value($RunBeforeJob);}}
            {{Former::text('RunAfterJob', 'RunAfterJob')->placeholder('RunAfterJob')->value($RunAfterJob);}}
            {{Former::text('RunAfterFailedJob', 'RunAfterFailedJob')->placeholder('RunAfterFailedJob')->value($RunAfterFailedJob);}}
            {{Former::text('ClientRunBeforeJob', 'ClientRunBeforeJob')->placeholder('ClientRunBeforeJob')->value($ClientRunBeforeJob);}}
            {{Former::text('ClientRunAfterJob', 'ClientRunAfterJob')->placeholder('ClientRunAfterJob')->value($ClientRunAfterJob);}}

            {{Former::text('RerunFailedLevels', 'RerunFailedLevels')->placeholder('RerunFailedLevels')->value($RerunFailedLevels);}}
            {{Former::text('MaxFullInterval', 'MaxFullInterval')->placeholder('MaxFullInterval')->value($MaxFullInterval);}}
            {{Former::text('SpoolSize', 'SpoolSize')->placeholder('SpoolSize')->value($SpoolSize);}}
            {{Former::text('Where', 'Where')->placeholder('Where')->value($Where);}}
            {{Former::text('AddPrefix', 'AddPrefix')->placeholder('AddPrefix')->value($AddPrefix);}}
            {{Former::text('RegexWhere', 'RegexWhere')->placeholder('RegexWhere')->value($RegexWhere);}}

            {{Former::text('RegexWhere', 'RegexWhere')->placeholder('RegexWhere')->value($RegexWhere);}}
            {{Former::text('MaximumConcurrentJobs', 'MaximumConcurrentJobs')->placeholder('MaximumConcurrentJobs')->value($MaximumConcurrentJobs);}}
            {{Former::text('RescheduleInterval', 'RescheduleInterval')->placeholder('RescheduleInterval')->value($RescheduleInterval);}}
            {{Former::text('PrefixLinks', 'PrefixLinks')->placeholder('PrefixLinks')->value($PrefixLinks);}}
            {{Former::text('RescheduleOnError', 'RescheduleOnError')->placeholder('RescheduleOnError')->value($RescheduleOnError);}}
            {{Former::text('Replace', 'Replace')->placeholder('Replace')->value($Replace);}}
            
            {{Former::text('AllowMixedPriority', 'AllowMixedPriority')->placeholder('AllowMixedPriority')->value($AllowMixedPriority);}}
            {{Former::text('Priority', 'Priority')->placeholder('Priority')->value($Priority);}}
            {{Former::text('AllowHigherDuplicates', 'AllowHigherDuplicates')->placeholder('AllowHigherDuplicates')->value($AllowHigherDuplicates);}}
            {{Former::text('CancelLowerLevelDuplicates', 'CancelLowerLevelDuplicates')->placeholder('CancelLowerLevelDuplicates')->value($CancelLowerLevelDuplicates);}}
            {{Former::text('CancelQueuedDuplicates', 'CancelQueuedDuplicates')->placeholder('CancelQueuedDuplicates')->value($CancelQueuedDuplicates);}}

            
            {{Former::text('RescheduleTimes', 'RescheduleTimes')->placeholder('RescheduleTimes')->value($RescheduleTimes);}}
            {{Former::text('AllowDuplicateJobs', 'AllowDuplicateJobs')->placeholder('AllowDuplicateJobs')->value($AllowDuplicateJobs);}}
            {{Former::text('CancelRunningDuplicates', 'CancelRunningDuplicates')->placeholder('CancelRunningDuplicates')->value($CancelRunningDuplicates);}}
            {{Former::text('SpoolAttributes', 'SpoolAttributes')->placeholder('SpoolAttributes')->value($SpoolAttributes);}}
            {{Former::text('WritePartAfterJob', 'WritePartAfterJob')->placeholder('WritePartAfterJob')->value($WritePartAfterJob);}}

            {{Former::text('Run', 'Run')->placeholder('Run')->value($Run);}}
            {{Former::text('PruneFiles', 'PruneFiles')->placeholder('PruneFiles')->value($PruneFiles);}}
    






        </div>
    <div>
    {{Former::close();}}
    </div>  
 