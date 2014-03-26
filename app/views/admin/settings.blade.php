@extends('admin._layouts.default')
@section('main')
{{ Former::horizontal_open('admin/savesettings','post',array('class'=>'ajax', 'data-replace' => '.response', 'id' => 'settings')) }}
<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span4">
            <h3>{{ HTML::image('assets/img/settings.png') }} Settings</h3>
        </div>
    </div>
</div>
<div class="row-fluid">
   <div class="span12 box-content ">
        <div class="span5 box ">
            <div class="box-header well">
                <h2><i class="icon-barcode"></i> Server Settings </h2>
            </div>
            <div class="box-content">
               {{Former::text('servername', 'Server Name')->prepend('<i class="icon-fam-server-add"></i>')->placeholder('Server Name')->autofocus(); }}
               {{Former::email('adminemail', 'Administrator Email')->prepend('<i class="icon-fam-email-add"></i>')->placeholder('Administrator Email');}}
                {{Former::text('confdir', 'Bacula Directory')->prepend('<i class="icon-fam-world-link"></i>')->placeholder( '/etc/bacula/' );}}
            </div>
        </div>
        <div class="span7 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i>Ldap & Active Directory Configuration</h2>
            </div>
            <div class="box-content">
                {{  Former::checkbox('ldapon')->text('Yes')->value('1') }}
                {{Former::text('ldapserver', 'Ldap Serder')->prepend('<i class="icon-fam-server-add"></i>')->placeholder('server.domain.com');}}
                {{Former::text('ldapdomain', 'Domain Name')->prepend('<i class="icon-fam-computer-add"></i>')->placeholder('@domain.com');}}
                {{Former::text('ldapbasedn','Base Dn')->prepend('<i class="icon-fam-add"></i>')->placeholder('DC=local,DC=domain,DC=com');}}
                {{Former::text('ldapport','Ldap Port')->prepend('<i class="icon-fam-door"></i>')->placeholder('389');}}
                {{Former::text('ldapuser', 'Ldap User')->prepend('<i class="icon-fam-server-add"></i>')->placeholder('Ldap User');}}
                {{Former::password('ldappassword','Ldap Password')->prepend('<i class="icon-fam-key-add"></i>')->placeholder('Password');}}
                 <div class="controls">
                    <a href="#" id="testLDAP" name="testLDAP" class="btn btn-small btn-info ajax"  data-method="post" data-replace=".response-check-db"><i class="icon-fam-exclamation userstate"></i> Check Ldap Connection </a>

                     <a href="#"  id="syncLDAP" class="btn btn-small btn-success ajax"  data-method="post" data-replace=".response-check-db"><i class="icon-fam-user userstate"></i> Syncronize Users </a>
                </div>
            </div>

        </div>
    </div>
    <div class="span10 box-content">
        <center>
            <div class="response"></div>
            {{Form::submit( ' Save ', array('class' => 'btn btn-large btn-primary' ));}}
        </center>
    </div>
</div>

{{Form::close();}}
@endsection
