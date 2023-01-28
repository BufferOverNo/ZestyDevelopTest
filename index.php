<?php

require_once 'OpeningHours.php';

// Initialize the class
$openingHours = new OpeningHours();

// Test 1
echo "Test 1: \n";
$currentTime = new DateTime();
$currentDay = $currentTime->format('D');
$isOpen = $openingHours->isOpen($currentTime);
if ($isOpen) {
    echo "The store is currently open. Today's hours are: " . $openingHours->getHours($currentDay) . "\n";
} else {
    echo "The store is currently closed. Today's hours are: " . $openingHours->getHours($currentDay) . "\n";
}

// Test 2
echo "Test 2: \n";
$currentTime = new DateTime('Tue 18:00');
$nextOpen = $openingHours->nextOpening($currentTime);
echo "The store will be open next on: " . $nextOpen->format('l, F jS H:i') . "\n";

// Test 3
echo "Test 3: \n";
$chicagoTime = new DateTimeZone('America/Chicago');
$currentTime = new DateTime('now', $chicagoTime);
$isOpen = $openingHours->isOpen($currentTime);
if ($isOpen) {
    echo "The store is currently open in Chicago.\n";
} else {
    echo "The store is currently closed in Chicago.\n";
}