<?php
$sdir = dirname(__FILE__);
system ( 'cd '.$sdir.'; ~/.rvm/gems/ruby-2.6.3/bin/jekyll build;' );
system ( 'cd '.$sdir.'; git pull;' );
$sdate = date('Y-m-d H:i');
system ( 'cd '.$sdir.'; git add -A;' );
system ( 'cd '.$sdir.'; git commit -a -m "Updated db '.$sdate.'";' );
system ( 'cd '.$sdir.'; git push origin master ;' );

?>
