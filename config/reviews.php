<?php

return [
	// The reviews model
	'model' => \App\Models\Review::class,

	// The models that can be reviewed
	'reviewables' => [
		// \App\Models\Product::class,
	],

	// Also change the value in the UI: in the ReviewsStars component
	'rating_max' => 5,
];