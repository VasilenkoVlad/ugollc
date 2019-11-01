<?php
class Geocode {

	public function getStoreHouseDistance( $userAddressLatitude, $userAddressLongitude,$boundryDetails ) {  
		$storeConstantLatitude 		= $boundryDetails['latitude'];  //define in constant file after development
		$storeConstantLongitude 	= $boundryDetails['longitude'];	////define in constant file after development
	    $earth_radius = 6371;
	    $degreeLatitudeDiffrence 	= deg2rad( $userAddressLatitude - $storeConstantLatitude );  
	    $degreeLongitudeDiffrence 	= deg2rad( $userAddressLongitude - $storeConstantLongitude );  
	    $latitudeDiffrence = sin( $degreeLatitudeDiffrence/2 ) * sin( $degreeLatitudeDiffrence/2 ) + cos(deg2rad($storeConstantLatitude)) * cos( deg2rad( $userAddressLatitude ) ) * sin($degreeLongitudeDiffrence/2 ) * sin( $degreeLongitudeDiffrence/2 );  
	    $areaDiffrence = 2 * asin(sqrt( $latitudeDiffrence ));  
	    $diffrenceInKM = $earth_radius * $areaDiffrence;
	    return $diffrenceInKM;  
	}
		
}