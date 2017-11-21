$.validator.addMethod( "lettersonly", function( value, element ) {
	return this.optional( element ) ||/^[a-z0-9]+$/i.test( value );
}, "Letters only please" );