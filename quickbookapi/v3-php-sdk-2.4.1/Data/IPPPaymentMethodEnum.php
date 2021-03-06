<?php

/**
 * @xmlNamespace http://schema.intuit.com/finance/v3
 * @xmlType string
 * @xmlName IPPPaymentMethodEnum
 * @var IPPPaymentMethodEnum
 * @xmlDefinition 
				Product: ALL
				Description: Enumeration of payment methods when receiving a customer payment of paying for goods.
			
 */
class IPPPaymentMethodEnum
	{

		/**                                                                       
		* Initializes this object, optionally with pre-defined property values    
		*                                                                         
		* Initializes this object and it's property members, using the dictionary
		* of key/value pairs passed as an optional argument.                      
		*                                                                         
		* @param dictionary $keyValInitializers key/value pairs to be populated into object's properties 
		* @param boolean $verbose specifies whether object should echo warnings   
		*/                                                                        
		public function __construct($keyValInitializers=array(), $verbose=FALSE)
		{
			foreach($keyValInitializers as $initPropName => $initPropVal)
			{
				if (property_exists('IPPPaymentMethodEnum',$initPropName))
				{
					$this->{$initPropName} = $initPropVal;
				}
				else
				{
					if ($verbose)
						echo "Property does not exist ($initPropName) in class (".get_class($this).")";
				}
			}
		}

		/**
		 * @xmlType value
		 * @var string
		 */
		public $value;	const IPPPAYMENTMETHODENUM_AMEX = "AmEx";
	const IPPPAYMENTMETHODENUM_CASH = "Cash";
	const IPPPAYMENTMETHODENUM_CHECK = "Check";
	const IPPPAYMENTMETHODENUM_DEBITCARD = "DebitCard";
	const IPPPAYMENTMETHODENUM_DISCOVER = "Discover";
	const IPPPAYMENTMETHODENUM_ECHECK = "ECheck";
	const IPPPAYMENTMETHODENUM_GIFTCARD = "GiftCard";
	const IPPPAYMENTMETHODENUM_MASTERCARD = "MasterCard";
	const IPPPAYMENTMETHODENUM_OTHER = "Other";
	const IPPPAYMENTMETHODENUM_OTHERCREDITCARD = "OtherCreditCard";
	const IPPPAYMENTMETHODENUM_VISA = "Visa";

} // end class IPPPaymentMethodEnum
