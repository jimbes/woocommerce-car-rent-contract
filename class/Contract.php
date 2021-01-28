<?php


class Contract {
	private $createDate;
	private $update;
	private $fkIdCommande;
	private $fkIdUser;
	private $status;  // 1:Start; 2:On going; 3:End

	// User
	private $fname;
	private $lname;
	private $dob;
	private $driverlicense;
	private $driverdate;
	private $address;

	// Propiétaire
	private $proprioName;

	// Car
	private $model;
	private $idCar;
	private $fuel;

	// Location
	private $startDate;
	private $endDate;
	private $deliveryAddress;
	private $kilometers;
	private $levelFuel;
	private $price;

	// Option
	private $Insurance;

	// Checkup
	private $front;
	private $imgFront;
	private $left;
	private $imgLeft;
	private $back;
	private $imgBack;
	private $right;
	private $imgRight;

}