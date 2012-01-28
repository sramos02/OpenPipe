package OpenPipe::Apache2::Handler::Steam; 

use strict;
use warnings;

use base qw(Apache2::Filter);

use APR::Brigade ();
use APR::Bucket ();

use Apache2::Const -compile => 'OK';
use APR::Const     -compile => ':common';
use APR::Table ();

sub handler { 
    my ($f, $bb) = @_;

  #Unset our Content-Length header since we will be changing
  # the content's length.
    unless( $f->ctx ) {
	   $f->ctx(1); 
       $f->r->headers_out->unset('Content-Length'); 
    }

    my $bb_ctx = APR::Brigade->new($f->c->pool, $f->c->bucket_alloc);

    while (!$bb->is_empty) {
        my $b = $bb->first;

        $b->remove;

        if ($b->is_eos) {

            $bb_ctx->insert_tail($b);
            last;
        }

        $bb_ctx->insert_tail($b);
    }


	
    my $rv = $f->next->pass_brigade($bb_ctx);

	my $sub = $f->r->lookup_uri("/openpipe/subindex.html");
	$sub->run();
	

	#if ($b->is_eos) {
	#	my $sub = $f->r->lookup_uri("/openpipe/subindex.html");
	#	$sub->run();
	#}
	
	
    return $rv unless $rv == APR::Const::SUCCESS;
	

    
	return Apache2::Const::OK;
   
}
1;