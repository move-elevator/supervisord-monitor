<?php

if(isset($_GET['mute'])){
	$mute = ($_GET['mute']?(time()+600):0);
	if($mute) setcookie('mute',$mute,$mute,'/');
	else setcookie('mute',0,time()-1,'/');
	Redirect();
}
$muted = (isset($_COOKIE['mute'])?$_COOKIE['mute']:0);
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Supervisord Monitoring</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link type="text/css" rel="stylesheet" href="<?php echo site_url('/css/bootstrap.min.css');?>"/>
    <link type="text/css" rel="stylesheet" href="<?php echo site_url('/css/bootstrap-responsive.min.css');?>"/>
    <link type="text/css" rel="stylesheet" href="<?php echo site_url('/css/custom.css');?>"/>
    <script type="text/javascript" src="<?php echo site_url('/js/jquery-1.10.1.min.js');?>"></script>
    <script type="text/javascript" src="<?php echo site_url('/js/bootstrap.min.js');?>"></script>

    <noscript>
	<?php
	if($this->config->item('refresh')){ ?>
	<meta http-equiv="refresh" content="<?php echo $this->config->item('refresh');?>">
	<?php } ?>
	</noscript>
</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		  <a class="brand" href="<?php echo site_url('');?>">Support Center</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
			<li class="active"><a href="<?php echo site_url();?>">Home</a></li>
              <li><a href="?mute=<?php echo $muted?0:1;?>"><i class="icon-music icon-white"></i>&nbsp;<?php
			if($muted){
				echo "Unmute";
			}else{
				echo "Mute";
			}
		;?></a></li>
		<li><a href="<?php echo site_url();?>">Refresh <b id="refresh">(<?php echo $this->config->item('refresh');?>)</b> &nbsp;</a></li>
              <li><a href="mailto:martin@lazarov.bg">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	

	<div class="container" style="margin-top: 40px" id="container">
	
		<?php
		if($muted){
			echo '<div class="row"><div class="span4 offset4 label label-important" style="padding:10px;margin-bottom:20px;text-align:center;">';
			echo 'Sound muted for '.timespan(time(),$muted).' <span class="text-center"><a href="?mute=0" style="color:white;"><i class="icon-music icon-white"></i> Unmute</a></span></div></div>';
		}
	
		?>
		<div class="row">
				<?php
				$alert = false;

				foreach($list as $name=>$procs){
					$parsed_url = parse_url($cfg[$name]['url']);
                    if ( isset($cfg[$name]['username']) && isset($cfg[$name]['password']) ){
                        $base_url = 'http://' . $cfg[$name]['username'] . ':' . $cfg[$name]['password'] . '@';
                    }else{
                        $base_url = 'http://';
                    }
                    $ui_url = $base_url . $parsed_url['host'] . ':' . $cfg[$name]['port']. '/';
				?>
				<table class="table table-condensed">
					<tr>
                        <th colspan="4">
                            <a href="<?php echo $ui_url; ?>"><?php echo $name; ?></a> <?php if($this->config->item('show_host')){ ?><i><?php echo $parsed_url['host']; ?></i><?php } ?>
                            <?php if(isset($cfg[$name]['username'])){echo '<i class="icon-lock icon-green" style="color:blue" title="Authenticated server connection"></i>';}?>
                            <span class="server-btns text-center">
                                <a href="/control/stopall/<?php echo $name; ?>" class="btn btn-mini btn-inverse" type="button"><i class="icon-stop icon-white"></i> Stop all</a>
                                <a href="/control/startall/<?php echo $name; ?>" class="btn btn-mini btn-success" type="button"><i class="icon-play icon-white"></i> Start all</a>
                                <a href="/control/restartall/<?php echo $name; ?>" class="btn btn-mini btn-primary" type="button"><i class="icon icon-refresh icon-white"></i> Restart all</a>
                            </span>
                        </th>
                        <th>
                            <button type="button" data-project="<?php echo md5($name); ?>" class="btn btn-success pull-right show-all">
                                Alle Tasks anzeigen ⬇
                            </button>
                        </th>
                    </tr>
					<?php
					$CI = &get_instance();
					foreach($procs as $item){
						
						if($item['group'] != $item['name']) $item_name = $item['group'].":".$item['name'];
						else $item_name = $item['name'];
						
						$check = $CI->_request($name,'readProcessStderrLog',array($item_name,-1000,0));
						if(is_array($check)) $check = print_r($check,1);

						if(!is_array($item)){ echo '<tr><td colspan="4">'.$item.'</td></tr>';continue;}
						$pid = $uptime = '&nbsp;';
						$status = $item['statename'];

						if($status=='RUNNING'){
							$class = 'success';
							list($pid,$uptime) = explode(",",$item['description']);
						}

						elseif($status=='STARTING') $class = 'info';
						elseif($status=='FATAL') $class = 'important';
						elseif($status=='STOPPED') $class = 'inverse';
						else $class = 'error';

                        $uptime = str_replace("uptime ","",$uptime);
						?>


						<tr class="<?php if($status === 'FATAL') { echo 'alert-tr'; } elseif($check) {echo 'warning-tr'; } else { echo 'hide normal-tr ' . md5($name);  } ?>">
							<td width="50%" style="width: 50%;"><?php
								echo $item_name;
								if($check){
									$alert = true;
		echo '<span class="text-center"><a href="/control/clear/'.$name.'/'.$item_name.'" id="'.$name.'_'.$item_name.'" onclick="return false" data-toggle="popover" data-message="'.htmlspecialchars($check).
			'" data-original-title="'.$item_name.'@'.$name.'" class="pop btn btn-mini btn-danger"><img src="/img/alert_icon.png" /></a></span>';
								}
								?>
							</td>
							<td width="20%" style="text-align: center; width: 20%;"><span class="label label-<?php echo $class;?>"><?php echo $status;?></span></td>
							<td width="10%" style="text-align: center; width: 10%;"><?php echo $uptime;?></td>
							<td width="20%" style="text-align: center; width: 20%;">
								<!--div class="btn-group">
									<button class="btn btn-mini">Action</button>
									<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown">
										<span class="caret"></span>
									</button>
									<ul class="dropdown-menu">
										<li><a href="test">Restart</a></li>
										<li><a href="zz">Stop</a></li>
									</ul>
								</div//-->
								<?php if($status=='RUNNING'){ ?>
								<a href="/control/stop/<?php echo $name.'/'.$item_name;?>" class="btn btn-mini btn-inverse text-center" type="button"><i class="icon-stop icon-white"></i></a>
								<?php } if($status=='STOPPED' || $status == 'EXITED'){ ?>
								<a href="/control/start/<?php echo $name.'/'.$item_name;?>" class="btn btn-mini btn-success text-center" type="button"><i class="icon-play icon-white"></i></a>
								<?php } ?>
							</td>
						</tr>
						<?php
					}

					?>
				</table>				
			</div>
				<?php
				}
                echo $this->config->item('enable_alarm');
               # echo '<div class="omg-alarm"></div>';

                if($status === 'FATAL' && !$muted && $this->config->item('enable_alarm')){
					echo '<embed height="0" width="0" src="/sounds/alert.mp3">';
				}
				if($alert){
					echo '<title>!!! WARNING !!!</title>';
				}else{
					echo '<title>Support center</title>';
				}
				
				?>
	</div>

    </div> <!-- /container -->
	
	<div class="footer" id="footer">
		<p>Powered by <a href="https://github.com/mlazarov/supervisord-monitor" target="_blank">Supervisord Monitor</a> | Page rendered in <strong>{elapsed_time}</strong> seconds</p>
	</div>

    <script>
	function show_content($param){
		stopTimer();
		$time = new Date();
		$message = $(this).data('message')+"\n"+$time+"\n"+$time.toDateString();
		$title = $(this).data('original-title');
		$content = '<div class="well" style="padding:20px;">'+nl2br($message)+'</div>';
		$content+= '<div class="pull-left"><form method="get" action="<?php echo $this->config->item('redmine_url');?>" style="display:inline" target="_blank">';
		$content+= '<input type="hidden" name="issue[subject]" value="'+$title+'"/>';
		$content+= '<input type="hidden" name="issue[description]" value="'+$message+'"/>';
		$content+= '<input type="hidden" name="issue[assigned_to_id]" value="<?php echo $this->config->item('redmine_assigne_id');?>"/>';
		$content+= '<input type="submit" class="btn btn-small btn-inverse" value="Start New Ticket"/>';
		$content+= '</form></div>';
		$content+= '<div class="text-center"><a href="#" onclick="$(\'#'+$(this).attr('id')+'\').popover(\'hide\');startTimer();" class="btn btn-small btn-primary">ok</a>&nbsp;&nbsp;';
		$content+= '<a href="'+$(this).attr('href')+'" class="btn btn-small btn-danger">Clear</a> &nbsp; </div>';
		return $content;
	}
	$('.pop').popover(
		{content: show_content,html:true,placement: 'bottom'}
	);
	var $refresh = <?php echo $this->config->item('refresh');?>;
	var $timer = false;
	$(window).load(function() {
		startTimer();
  	});
	function stopTimer(){
		$('#refresh').html('(p)');
		clearInterval($timer);
	}
	function startTimer(){
		if($timer)  stopTimer();
		$timer = setInterval(timer,999);
	}
	function timer(){
		$refresh--;
		$('#refresh').html('('+$refresh+')');
		if($refresh<=0){
			location.href="/";
		}
		
	}
	function nl2br (str, is_xhtml) {
		var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>';
		return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
	}


    $( ".show-all" ).click(function() {
        var project = $(this).data('project');
        $( "." + project).toggleClass('hide');
    });

    </script>
</body>
</html>
