$( function() {

    if ( $( "span.countdown" ).length ) {
        countdown.setLabels(
            ' מילישנייה| שנייה| דקה| שעה| יום |שבוע| חודש |שנה| עשור |מאה |אלף',
            ' מילישניות| שניות| דקות| שעות| ימים| שבועות| חודשים| שנים| עשורים| מאות| אלפים',
            ' ו-',
            ', '
        );
        const endTime = parseInt( $( "span.countdown" ).data( "end" ) ) * 1000;
        const ctFn = () => {
            if ( endTime - 1000 <= Date.now() ) {
                $( ".countdown" ).html( "0 שניות" );
                setTimeout( () => location.reload(), 1000 );
                return;
            }
            $( ".countdown" ).html( countdown( endTime ).toString() );
            requestAnimationFrame( ctFn );
        };
        ctFn();
    }

    function updateScrollStatus() {
        if ( $( window ).scrollTop() > $( window ).height() ) {
            $( "body" ).addClass( "passed-top" );
        } else {
            $( "body" ).removeClass( "passed-top" );
        }
    }
    updateScrollStatus();
    $( window ).on( "scroll", updateScrollStatus );

} );