<?php
/************************************************************************
 *
 * Copyright 2024 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Adobe.
 * ************************************************************************
 */
declare(strict_types=1);

namespace Magento\GraphQl\Quote;

use Magento\Catalog\Api\Data\ProductCustomOptionInterface;
use Magento\Catalog\Model\Config\Source\ProductPriceOptionsInterface;
use Magento\Catalog\Test\Fixture\Product;
use Magento\Framework\GraphQl\Query\Uid;
use Magento\Framework\ObjectManagerInterface;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Magento\Quote\Test\Fixture\GuestCart;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorage;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\GraphQlAbstract;

/**
 * Test for getting prices for products with custom options from cart query
 */
class ProductsWithCustomOptionsCartPricesTest extends GraphQlAbstract
{
    /**
     * @var QuoteIdToMaskedQuoteIdInterface
     */
    private $quoteIdToMaskedQuoteIdInterface;

    /**
     * @var DataFixtureStorage
     */
    private $fixtures;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var Uid
     */
    private $uidEncoder;

    protected function setUp(): void
    {
        $this->objectManager = Bootstrap::getObjectManager();
        $this->quoteIdToMaskedQuoteIdInterface = $this->objectManager->get(QuoteIdToMaskedQuoteIdInterface::class);
        $this->fixtures = $this->objectManager->get(DataFixtureStorageManager::class)->getStorage();
        $this->uidEncoder = $this->objectManager->create(Uid::class);
    }

    #[
        DataFixture(GuestCart::class, as: 'quote'),
        DataFixture(
            Product::class,
            [
                'sku' => 'simple1',
                'price' => 30,
                'special_price' => 15,
                'options' => [
                    [
                        'title' => 'option1',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price' => 10
                    ],
                    [
                        'title' => 'option2',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price' => 40,
                        'is_require' => false
                    ],
                    [
                        'title' => 'option3',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price' => 0
                    ],
                ]
            ],
            'product'
        )
    ]
    public function testProductsWithOneCustomOptionEnteredWithFixedPrice()
    {
        $cart = $this->fixtures->get('quote');
        $maskedQuoteId = $this->quoteIdToMaskedQuoteIdInterface->execute((int) $cart->getId());
        $product = $this->fixtures->get('product');
        $sku = $product->getSku();

        $options = $product->getOptions();
        $optionUid = $this->uidEncoder->encode(
            'custom-option' . '/' . $options[0]->getData()['option_id']
        );
        $optionUid2 = $this->uidEncoder->encode(
            'custom-option' . '/' . $options[2]->getData()['option_id']
        );
        $this->graphQlMutation(
            $this->addProductWithOptionMutation($maskedQuoteId, 2, $sku, $optionUid, $optionUid2)
        );

        $query = $this->getCartQuery($maskedQuoteId);
        $response = $this->graphQlQuery($query);

        $expectedResponse = [
            "cart" =>  [
                "itemsV2" => [
                    "items" => [
                        0 => [
                            "prices" => [
                                "price" => [
                                    "value" => 25,
                                    "currency" => "USD"
                                ],
                                "row_total" => [
                                    "value" => 50,
                                    "currency" => "USD"
                                ],
                                "original_row_total" => [
                                    "value" => 80,
                                    "currency" => "USD"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    #[
        DataFixture(GuestCart::class, as: 'quote'),
        DataFixture(
            Product::class,
            [
                'sku' => 'simple1',
                'price' => 30,
                'special_price' => 15,
                'options' => [
                    [
                        'title' => 'option1',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price_type' => ProductPriceOptionsInterface::VALUE_PERCENT,
                        'price' => 10,
                    ],
                    [
                        'title' => 'option2',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price' => 40,
                        'is_require' => false
                    ],
                    [
                        'title' => 'option3',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price' => 0
                    ],
                ]
            ],
            'product'
        )
    ]
    public function testProductsWithOneCustomOptionEnteredWithPercentPrice()
    {
        $cart = $this->fixtures->get('quote');
        $maskedQuoteId = $this->quoteIdToMaskedQuoteIdInterface->execute((int) $cart->getId());
        $product = $this->fixtures->get('product');
        $sku = $product->getSku();

        $options = $product->getOptions();
        $optionUid = $this->uidEncoder->encode(
            'custom-option' . '/' . $options[0]->getData()['option_id']
        );
        $optionUid2 = $this->uidEncoder->encode(
            'custom-option' . '/' . $options[2]->getData()['option_id']
        );

        $this->graphQlMutation(
            $this->addProductWithOptionMutation($maskedQuoteId, 2, $sku, $optionUid, $optionUid2)
        );

        $query = $this->getCartQuery($maskedQuoteId);
        $response = $this->graphQlQuery($query);

        $expectedResponse = [
            "cart" =>  [
                "itemsV2" => [
                    "items" => [
                        0 => [
                            "prices" => [
                                "price" => [
                                    "value" => 16.5,
                                    "currency" => "USD"
                                ],
                                "row_total" => [
                                    "value" => 33,
                                    "currency" => "USD"
                                ],
                                "original_row_total" => [
                                    "value" => 66,
                                    "currency" => "USD"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    #[
        DataFixture(GuestCart::class, as: 'quote'),
        DataFixture(
            Product::class,
            [
                'sku' => 'simple1',
                'price' => 30,
                'special_price' => 15,
                'options' => [
                    [
                        'title' => 'option1',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price_type' => ProductPriceOptionsInterface::VALUE_PERCENT,
                        'price' => 10,
                    ],
                    [
                        'title' => 'option2',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'is_require' => false,
                        'price' => 40.0
                    ],
                    [
                        'title' => 'option3',
                        'type' => ProductCustomOptionInterface::OPTION_TYPE_FIELD,
                        'price' => 50.0
                    ]
                ]
            ],
            'product'
        )
    ]
    public function testProductsWithOneCustomOptionEnteredWithPercentPriceAndOneWithFixedPrice()
    {
        $cart = $this->fixtures->get('quote');
        $maskedQuoteId = $this->quoteIdToMaskedQuoteIdInterface->execute((int) $cart->getId());
        $product = $this->fixtures->get('product');
        $sku = $product->getSku();

        $options = $product->getOptions();
        $optionUid = $this->uidEncoder->encode(
            'custom-option' . '/' . $options[0]->getData()['option_id']
        );
        $optionUid2 = $this->uidEncoder->encode(
            'custom-option' . '/' . $options[2]->getData()['option_id']
        );

        $this->graphQlMutation(
            $this->addProductWithOptionMutation($maskedQuoteId, 2, $sku, $optionUid, $optionUid2)
        );

        $query = $this->getCartQuery($maskedQuoteId);
        $response = $this->graphQlQuery($query);

        $expectedResponse = [
            "cart" =>  [
                "itemsV2" => [
                    "items" => [
                        0 => [
                            "prices" => [
                                "price" => [
                                    "value" => 66.5,
                                    "currency" => "USD"
                                ],
                                "row_total" => [
                                    "value" => 133,
                                    "currency" => "USD"
                                ],
                                "original_row_total" => [
                                    "value" => 166,
                                    "currency" => "USD"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * Create cart query with prices data
     *
     * @param string $maskedQuoteId
     * @return string
     */
    private function getCartQuery(string $maskedQuoteId): string
    {
        return <<<QUERY
{
  cart(cart_id: "{$maskedQuoteId}") {
    itemsV2 {
      items {
        prices {
          price {
            value
            currency
          }
          row_total {
            value
            currency
          }
          original_row_total {
            value
            currency
          }
        }
      }
    }
  }
}
QUERY;
    }

    /**
     * Add product with custom option to the cart
     *
     * @param string $cartId
     * @param int $qty
     * @param string $sku
     * @param string $optionUid
     * @param string $optionUid2
     * @return string
     */
    private function addProductWithOptionMutation(
        string $cartId,
        int $qty,
        string $sku,
        string $optionUid,
        string $optionUid2
    ): string {
        return <<<QRY
mutation {
    addProductsToCart(
        cartId: "{$cartId}"
        cartItems: [
            {
                quantity: {$qty}
                sku: "{$sku}"
                entered_options: [
                    {
                        uid:"{$optionUid}",
                        value:"value1"
                    }
                    {
                        uid:"{$optionUid2}",
                        value:"value2"
                    }
                ]
            }
        ]
    ) {
        cart {
            id
        }
    }
}
QRY;
    }
}
