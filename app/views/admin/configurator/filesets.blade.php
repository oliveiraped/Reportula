

<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
                <h3>{{ HTML::image('assets/img/user.png') }} Fileset - {{ $Name }}</h3>
        </div>
        <div class="span4 pull-right">
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
            {{Former::text('IgnoreFileSetChanges', 'IgnoreFileSetChanges')->placeholder('IgnoreFileSetChanges');}}
            {{Former::text('EnableVSS', 'EnableVSS')->placeholder('EnableVSS');}}
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a data-target="#include" data-toggle="tab" >Include Files</a></li>
                <li><a data-target="#exclude" data-toggle="tab" >Exclude Files</a></li>
            </ul>            
            <div class="tab-content">
                <div class="tab-pane active" id="include">
                    @if ( count($cfgfilesetinclude ))
                        <div class="span6" id="include">
                            <h4>Files Include</h4>
                            <div class="my-form">    
                                  <a class="add-box" href="#">Add More</a>    
                                    @foreach ( $cfgfilesetinclude as $includes )
                                        <p class="text-box"> <input type="text" name="include[]" value="{{ $includes['file'] }}" /></p>
                                    @endforeach
                             </div>
                        </div>
                        <div class="span6">
                            <h4>Files Otions</h4>
                            @foreach ( $cfgfilesetincludeoptions as $includesoptions )
                                <input type="text" name="includeoptions[]" value="{{ $options['value'] }}" />
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane" id="exclude">
                     @if ( count($cfgfilesetexclude))
                         <h4>Files Excludes</h4>
                         <div class="span6">
                            @foreach ( $cfgfilesetexclude as $excludes )
                                <input type="text" name="excludes[]" value="{{ $excludes['file'] }}" />
                            @endforeach 
                        </div>
                        <div class="span6">
                            <h4>Files Exclude Otions</h4>
                            @foreach ( $cfgfilesetexcludeoptions as $excludesoptions )
                               <!-- <input type="text" name="excludesoptions[]" value="{{ $options['value'] }}" /> -->
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    <div>
    {{Former::close();}}
</div>  


<script type="text/javascript">
jQuery(document).ready(function($){
    $('.my-form .add-box').click(function(){
        var n = $('.text-box').length + 1;
        var box_html = $('<p class="text-box"><input type="text" name="include[]" value="" id="box' + n + '" /> <a href="#" class="remove-box">Remove</a></p>');
        box_html.hide();
        $('.my-form p.text-box:first').before(box_html);
            box_html.fadeIn('slow');
        return false;
    });
    $('.my-form').on('click', '.remove-box', function(){
        $(this).parent().css( 'background-color', '#FF6C6C' );
        $(this).parent().fadeOut("slow", function() {
            $(this).remove();
            $('.box-number').each(function(index){
                $(this).text( index + 1 );
            });
        });
        return false;
    });
});
</script>