<?php

/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Checkout\Standard;


/**
 * Default implementation of standard checkout HTML client.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/checkout/standard/standard/subparts
	 * List of HTML sub-clients rendered within the checkout standard section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartPath = 'client/html/checkout/standard/standard/subparts';

	/** client/html/checkout/standard/address/name
	 * Name of the address part used by the checkout standard client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Checkout\Standard\Address\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/delivery/name
	 * Name of the delivery part used by the checkout standard client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Checkout\Standard\Delivery\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/payment/name
	 * Name of the payment part used by the checkout standard client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Checkout\Standard\Payment\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/summary/name
	 * Name of the summary part used by the checkout standard client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Checkout\Standard\Summary\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/order/name
	 * Name of the order part used by the checkout standard client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Checkout\Standard\Order\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/checkout/standard/process/name
	 * Name of the process part used by the checkout standard client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Checkout\Standard\Process\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2015.07
	 * @category Developer
	 */
	private $subPartNames = array( 'address', 'delivery', 'payment', 'summary', 'order', 'process' );
	private $cache;


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string HTML code
	 */
	public function getBody( $uid = '', array &$tags = array(), &$expire = null )
	{
		$context = $this->getContext();
		$view = $this->getView();

		try
		{
			$view = $this->setViewParams( $view, $tags, $expire );

			$html = '';
			foreach( $this->getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
			}
			$view->standardBody = $html;
		}
		catch( \Aimeos\Client\Html\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'client', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( \Aimeos\Controller\Frontend\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( \Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$error = array( $context->getI18n()->dt( 'client', 'A non-recoverable error occured' ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}

		/** client/html/checkout/standard/standard/template-body
		 * Relative path to the HTML body template of the checkout standard client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in client/html/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "standard" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "standard"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/standard/standard/template-header
		 */
		$tplconf = 'client/html/checkout/standard/standard/template-body';
		$default = 'checkout/standard/body-default.php';

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( $uid = '', array &$tags = array(), &$expire = null )
	{
		try
		{
			$view = $this->setViewParams( $this->getView(), $tags, $expire );

			$html = '';
			foreach( $this->getSubClients() as $subclient ) {
				$html .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
			}
			$view->standardHeader = $html;

			/** client/html/checkout/standard/standard/template-header
			 * Relative path to the HTML header template of the checkout standard client.
			 *
			 * The template file contains the HTML code and processing instructions
			 * to generate the HTML code that is inserted into the HTML page header
			 * of the rendered page in the frontend. The configuration string is the
			 * path to the template file relative to the templates directory (usually
			 * in client/html/templates).
			 *
			 * You can overwrite the template file configuration in extensions and
			 * provide alternative templates. These alternative templates should be
			 * named like the default one but with the string "standard" replaced by
			 * an unique name. You may use the name of your project for this. If
			 * you've implemented an alternative client class as well, "standard"
			 * should be replaced by the name of the new class.
			 *
			 * @param string Relative path to the template creating code for the HTML page head
			 * @since 2014.03
			 * @category Developer
			 * @see client/html/checkout/standard/standard/template-body
			 */
			$tplconf = 'client/html/checkout/standard/standard/template-header';
			$default = 'checkout/standard/header-default.php';

			return $view->render( $view->config( $tplconf, $default ) );
		}
		catch( \Exception $e )
		{
			$this->getContext()->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );
		}
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** client/html/checkout/standard/decorators/excludes
		 * Excludes decorators added by the "common" option from the checkout standard html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/checkout/standard/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/checkout/standard/decorators/global
		 * @see client/html/checkout/standard/decorators/local
		 */

		/** client/html/checkout/standard/decorators/global
		 * Adds a list of globally available decorators only to the checkout standard html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/checkout/standard/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/checkout/standard/decorators/excludes
		 * @see client/html/checkout/standard/decorators/local
		 */

		/** client/html/checkout/standard/decorators/local
		 * Adds a list of local decorators only to the checkout standard html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Checkout\Decorator\*") around the html client.
		 *
		 *  client/html/checkout/standard/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Checkout\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/checkout/standard/decorators/excludes
		 * @see client/html/checkout/standard/decorators/global
		 */

		return $this->createSubClient( 'checkout/standard/' . $type, $name );
	}


	/**
	 * Processes the input, e.g. store given values.
	 * A view must be available and this method doesn't generate any output
	 * besides setting view variables.
	 */
	public function process()
	{
		$view = $this->getView();
		$context = $this->getContext();

		try
		{
			parent::process();
		}
		catch( \Aimeos\Client\Html\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'client', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( \Aimeos\Controller\Frontend\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'controller/frontend', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( \Aimeos\MShop\Plugin\Provider\Exception $e )
		{
			$errors = array( $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$errors = array_merge( $errors, $this->translatePluginErrorCodes( $e->getErrorCodes() ) );

			$view->summaryErrorCodes = $e->getErrorCodes();
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $errors;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
		catch( \Exception $e )
		{
			$context->getLogger()->log( $e->getMessage() . PHP_EOL . $e->getTraceAsString() );

			$error = array( $context->getI18n()->dt( 'client', 'A non-recoverable error occured' ) );
			$view->standardErrorList = $view->get( 'standardErrorList', array() ) + $error;
		}
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}


	/**
	 * Sets the necessary parameter values in the view.
	 *
	 * @param \Aimeos\MW\View\Iface $view The view object which generates the HTML output
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return \Aimeos\MW\View\Iface Modified view object
	 */
	protected function setViewParams( \Aimeos\MW\View\Iface $view, array &$tags = array(), &$expire = null )
	{
		if( !isset( $this->cache ) )
		{
			$context = $this->getContext();

			$basketCntl = \Aimeos\Controller\Frontend\Factory::createController( $context, 'basket' );
			$view->standardBasket = $basketCntl->get();


			/** client/html/checkout/standard/url/step-active
			 * Name of the checkout process step to jump to if no previous step requires attention
			 *
			 * The checkout process consists of several steps which are usually
			 * displayed one by another to the customer. If the data of a step
			 * is already available, then that step is skipped. The active step
			 * is the one that is displayed if all other steps are skipped.
			 *
			 * If one of the previous steps misses some data the customer has
			 * to enter, then this step is displayed first. After providing
			 * the missing data, the whole series of steps are tested again
			 * and if no other step requests attention, the configured active
			 * step will be displayed.
			 *
			 * The order of the steps is determined by the order of sub-parts
			 * that are configured for the checkout client.
			 *
			 * @param string Name of the confirm standard HTML client
			 * @since 2014.07
			 * @category Developer
			 * @category User
			 * @see client/html/checkout/standard/standard/subparts
			 */
			$default = $view->config( 'client/html/checkout/standard/url/step-active', 'summary' );

			/** client/html/checkout/standard/onepage
			 * Shows all named checkout subparts at once for a one page checkout
			 *
			 * Normally, the checkout process is divided into several steps for entering
			 * addresses, select delivery and payment options as well as showing the
			 * summary page. This enables dependencies between two steps like showing
			 * delivery options based on the address entered by the customer. Furthermore,
			 * this is good way to limit the amount of information displayed which is
			 * preferred by mobile users.
			 *
			 * Contrary to that, a one page checkout displays all information on only
			 * one page and customers get an immediate overview of which information
			 * they have to enter and what options they can select from. This is an
			 * advantage if only a very limited amount of information must be entered
			 * or if there are almost no options to choose from and no dependencies
			 * between exist.
			 *
			 * Using this config options, shop developers are able to define which
			 * checkout subparts are combined to a one page view. Simply add the names
			 * of all checkout subparts to the list. Available checkout subparts for
			 * a one page checkout are:
			 * * address
			 * * delivery
			 * * payment
			 * * summary
			 *
			 * @param array List of checkout subparts name
			 * @since 2015.05
			 * @category Developer
			 */
			$onepage = $view->config( 'client/html/checkout/standard/onepage', array() );
			$onestep = ( !empty( $onepage ) ? array_shift( $onepage ) : $default ); // keep the first one page step

			$steps = (array) $context->getConfig()->get( $this->subPartPath, $this->subPartNames );
			$steps = array_diff( $steps, $onepage ); // remove all remaining steps in $onepage

			// use first step if default step isn't available
			$default = ( !in_array( $default, $steps ) ? reset( $steps ) : $default );
			$current = $view->param( 'c_step', $default );

			// use $onestep if the current step isn't available due to one page layout
			if( !in_array( $current, $steps ) ) {
				$current = $onestep;
			}

			// use $onestep if the active step isn't available due to one page layout
			if( isset( $view->standardStepActive ) && in_array( $view->standardStepActive, $onepage ) ) {
				$view->standardStepActive = $onestep;
			}

			$cpos = array_search( $current, $steps );

			if( !isset( $view->standardStepActive )
				|| ( ( $apos = array_search( $view->standardStepActive, $steps ) ) !== false
				&& $cpos !== false && $cpos < $apos )
			) {
				$view->standardStepActive = $current;
			}

			$view->standardSteps = $steps;


			$this->cache = $this->addNavigationUrls( $view, $steps, $view->standardStepActive );
		}

		return $this->cache;
	}


	/**
	 * Adds the "back" and "next" URLs to the view
	 *
	 * @param \Aimeos\MW\View\Iface $view View object
	 * @param array $steps List of checkout step names
	 * @param unknown $activeStep Name of the active step
	 * @return \Aimeos\MW\View\Iface Enhanced view object
	 * @since 2016.05
	 */
	protected function addNavigationUrls( \Aimeos\MW\View\Iface $view, array $steps, $activeStep )
	{
		/** client/html/checkout/standard/url/target
		 * Destination of the URL where the controller specified in the URL is known
		 *
		 * The destination can be a page ID like in a content management system or the
		 * module of a software development framework. This "target" must contain or know
		 * the controller that should be called by the generated URL.
		 *
		 * @param string Destination of the URL
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/standard/url/controller
		 * @see client/html/checkout/standard/url/action
		 * @see client/html/checkout/standard/url/config
		 */
		$cTarget = $view->config( 'client/html/checkout/standard/url/target' );

		/** client/html/checkout/standard/url/controller
		 * Name of the controller whose action should be called
		 *
		 * In Model-View-Controller (MVC) applications, the controller contains the methods
		 * that create parts of the output displayed in the generated HTML page. Controller
		 * names are usually alpha-numeric.
		 *
		 * @param string Name of the controller
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/standard/url/target
		 * @see client/html/checkout/standard/url/action
		 * @see client/html/checkout/standard/url/config
		*/
		$cCntl = $view->config( 'client/html/checkout/standard/url/controller', 'checkout' );

		/** client/html/checkout/standard/url/action
		 * Name of the action that should create the output
		 *
		 * In Model-View-Controller (MVC) applications, actions are the methods of a
		 * controller that create parts of the output displayed in the generated HTML page.
		 * Action names are usually alpha-numeric.
		 *
		 * @param string Name of the action
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/standard/url/target
		 * @see client/html/checkout/standard/url/controller
		 * @see client/html/checkout/standard/url/config
		*/
		$cAction = $view->config( 'client/html/checkout/standard/url/action', 'index' );

		/** client/html/checkout/standard/url/config
		 * Associative list of configuration options used for generating the URL
		 *
		 * You can specify additional options as key/value pairs used when generating
		 * the URLs, like
		 *
		 *  client/html/<clientname>/url/config = array( 'absoluteUri' => true )
		 *
		 * The available key/value pairs depend on the application that embeds the e-commerce
		 * framework. This is because the infrastructure of the application is used for
		 * generating the URLs. The full list of available config options is referenced
		 * in the "see also" section of this page.
		 *
		 * @param string Associative list of configuration options
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/checkout/standard/url/target
		 * @see client/html/checkout/standard/url/controller
		 * @see client/html/checkout/standard/url/action
		 * @see client/html/url/config
		*/
		$cConfig = $view->config( 'client/html/checkout/standard/url/config', array() );


		$bTarget = $view->config( 'client/html/basket/standard/url/target' );
		$bCntl = $view->config( 'client/html/basket/standard/url/controller', 'basket' );
		$bAction = $view->config( 'client/html/basket/standard/url/action', 'index' );
		$bConfig = $view->config( 'client/html/basket/standard/url/config', array() );


		$step = null;
		do {
			$lastStep = $step;
		}
		while( ( $step = array_shift( $steps ) ) !== null && $step !== $activeStep );


		if( $lastStep !== null ) {
			$param = array( 'c_step' => $lastStep );
			$view->standardUrlBack = $view->url( $cTarget, $cCntl, $cAction, $param, array(), $cConfig );
		} else {
			$view->standardUrlBack = $view->url( $bTarget, $bCntl, $bAction, array(), array(), $bConfig );
		}

		if( !isset( $view->standardUrlNext ) && ( $nextStep = array_shift( $steps ) ) !== null ) {
			$param = array( 'c_step' => $nextStep );
			$view->standardUrlNext = $view->url( $cTarget, $cCntl, $cAction, $param, array(), $cConfig );
		}
		// don't overwrite $view->standardUrlNext so order step URL is used

		return $view;
	}
}