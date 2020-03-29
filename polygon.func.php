<?php
    /* function is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $points_polygon = count($vertices_x) - 1;
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
            if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
                $c = !$c;
        }
        return $c;
    } */
    
    function is_in_polygon($vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $points_polygon = count($vertices_x) - 1;
        $i = $j = $c = $point = 0;
        for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
            $point = $i;
            if( $point == $points_polygon )
                $point = 0;
                if ( (($vertices_y[$point]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                    ($longitude_x < ($vertices_x[$j] - $vertices_x[$point]) * ($latitude_y - $vertices_y[$point]) / ($vertices_y[$j] - $vertices_y[$point]) + $vertices_x[$point]) ) )
                    $c = !$c;
        }
        return $c;
    }
    
    class Point {
        public $lat;
        public $long;
        function Point($lat, $long) {
            $this->lat = $lat;
            $this->long = $long;
        }
    }
    
    //the Point in Polygon function
    function pointInPolygon($p, $polygon) {
        //if you operates with (hundred)thousands of points
        set_time_limit(60);
        $c = 0;
        $p1 = $polygon[0];
        $n = count($polygon);
        
        for ($i=1; $i<=$n; $i++) {
            $p2 = $polygon[$i % $n];
            if ($p->long > min($p1->long, $p2->long)
                && $p->long <= max($p1->long, $p2->long)
                && $p->lat <= max($p1->lat, $p2->lat)
                && $p1->long != $p2->long) {
                    $xinters = ($p->long - $p1->long) * ($p2->lat - $p1->lat) / ($p2->long - $p1->long) + $p1->lat;
                    if ($p1->lat == $p2->lat || $p->lat <= $xinters) {
                        $c++;
                    }
                }
                $p1 = $p2;
        }
        // if the number of edges we passed through is even, then it's not in the poly.
        return $c%2!=0;
    }