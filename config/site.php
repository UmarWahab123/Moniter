<?php

return [

  'stripe_is_testing' =>  env('STRIPE_IS_TESTING', true),

  'stripe_testing_prodcut_id' =>  env('STRIPE_TESTING_PODUCT_ID', 'prod_LKZz1sNBcku0xe'),
  'stripe_testing_api_test_key' =>  env('STRIPE_TESTING_API_KEY', 'sk_test_51K9wCBCS6ywz511RRZUrmEDScPKTQqUlbJ6scLxvnm9C3DjelIyjBOsPySXzbjvfqQQbe55phDWQUr1xEDly3Kte00P87dqYWt'),


  'stripe_live_prodcut_id' =>  env('STRIPE_LIVE_PODUCT_ID', ''),
  'stripe_live_api_test_key' =>  env('STRIPE_LIVE_API_KEY', ''),

];
