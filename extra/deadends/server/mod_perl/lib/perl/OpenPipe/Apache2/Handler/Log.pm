package OpenPipe::Apache2::Handler::Log;

use strict;
use warnings;

use Apache2::RequestRec ();
use Apache2::SubRequest ();
use Apache2::RequestUtil ();
use Apache2::Connection ();


use Apache2::Const -compile => qw(OK DECLINED);

sub handler {
    #my $r = shift;

   	#my $sub = $r->lookup_uri("/openpipe/subindex.html");
	#$sub->run();
	
    return Apache2::Const::OK;
}
1;