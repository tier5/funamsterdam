<?php

class Marketify_Helpers {

    public function apply_date_query( $query_args, $timeframe ) {
        if ( 'all' == $timeframe ) {
            return $query_args;
        }

        if ( 'day' == $timeframe ) {
            $frame = date( 'd' );
        } else if ( 'week' == $timeframe ) {
            $frame = date( 'W' );
        } else if ( 'month' == $timeframe ) {
            $frame = date( 'm' );
        } elseif ( 'year' == $timeframe ) {
            $frame = date( 'Y' );
        }

        $query_args[ 'date_query' ][] = array( $timeframe => $frame );

        return $query_args;
    }

}
