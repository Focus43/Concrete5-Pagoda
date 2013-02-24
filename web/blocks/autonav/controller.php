<? defined('C5_EXECUTE') or die("Access Denied.");

	class AutonavBlockController extends Concrete5_Controller_Block_Autonav {
		
		/**
		 * This function is used by the getNavItems() method to generate the raw "pre-processed" nav items array.
		 * It also must exist as a separate function to preserve backwards-compatibility with older autonav templates.
		 * Warning: this function has side-effects -- if this gets called twice, items will be duplicated in the nav structure!
		 */
		function generateNav() {
			if (isset($this->displayPagesCID) && !Loader::helper('validation/numbers')->integer($this->displayPagesCID)) {
				$this->displayPagesCID = 0;
			}
			
			$db = Loader::db();
			// now we proceed, with information obtained either from the database, or passed manually from
			$orderBy = "";
			/*switch($this->orderBy) {
			switch($this->orderBy) {
				case 'display_asc':
					$orderBy = "order by Collections.cDisplayOrder asc";
					break;
				case 'display_desc':
					$orderBy = "order by Collections.cDisplayOrder desc";
					break;
				case 'chrono_asc':
					$orderBy = "order by cvDatePublic asc";
					break;
				case 'chrono_desc':
					$orderBy = "order by cvDatePublic desc";
					break;
				case 'alpha_desc':
					$orderBy = "order by cvName desc";
					break;
				default:
					$orderBy = "order by cvName asc";
					break;
			}*/
			switch($this->orderBy) {
				case 'display_asc':
					$orderBy = "order by Pages.cDisplayOrder asc";
					break;
				case 'display_desc':
					$orderBy = "order by Pages.cDisplayOrder desc";
					break;
				default:
					$orderBy = '';
					break;
			}
			$level = 0;
			$cParentID = 0;
			switch($this->displayPages) {
				case 'current':
					$cParentID = $this->cParentID;
					if ($cParentID < 1) {
						$cParentID = 1;
					}
					break;
				case 'top':
					// top level actually has ID 1 as its parent, since the home page is effectively alone at the top
					$cParentID = 1;
					break;
				case 'above':
					$cParentID = $this->getParentParentID();
					break;
				case 'below':
					$cParentID = $this->cID;
					break;
				case 'second_level':
					$cParentID = $this->getParentAtLevel(2);
					break;
				case 'third_level':
					$cParentID = $this->getParentAtLevel(3);
					break;
				case 'custom':
					$cParentID = $this->displayPagesCID;
					break;
				default:
					$cParentID = 1;
					break;
			}
			
			
			// jail the resolved subdomain as the top page (if we're dealing with wildcards)
			if( (defined('REQUEST_RESOLVE_WILDCARDS') && !is_null(REQUEST_SUB_DOMAIN)) || defined('REQUEST_SUB_DOMAIN_IS_ROOT') ){
				switch( $this->displayPages ){
					case 'current': case 'below':
						$cParentID = $this->cID;
						break;
						
					default: // overrides top, above, second, third, custom
						$_rootPath = REQUEST_RESOLVE_WILDCARDS_PATH . '/' . REQUEST_SUB_DOMAIN;
						if( defined('REQUEST_SUB_DOMAIN_IS_ROOT') ){
							$_rootPath = REQUEST_RESOLVE_ROOT_PATH;
						}
						$cParentID = Page::getByPath( $_rootPath )->getCollectionID();
						break;
				}
			}
			
			
			if ($cParentID != null) {
				
				/*
				
				$displayHeadPage = false;

				if ($this->displayPagesIncludeSelf) {
					$q = "select Pages.cID from Pages where Pages.cID = '{$cParentID}' and cIsTemplate = 0";
					$r = $db->query($q);
					if ($r) {
						$row = $r->fetchRow();
						$displayHeadPage = true;
						if ($this->displayUnapproved) {
							$tc1 = Page::getByID($row['cID'], "RECENT");
						} else {
							$tc1 = Page::getByID($row['cID'], "ACTIVE");
						}
						$tc1v = $tc1->getVersionObject();
						if (!$tc1v->isApproved() && !$this->displayUnapproved) {
							$displayHeadPage = false;
						}
					}
				}
				
				if ($displayHeadPage) {
					$level++;
				}
				*/
				
				if ($this->displaySubPages == 'relevant' || $this->displaySubPages == 'relevant_breadcrumb') {
					$this->populateParentIDArray($this->cID);
				}
				
				$this->getNavigationArray($cParentID, $orderBy, $level);
				
				// if we're at the top level we add home to the beginning
				if ($cParentID == 1) {
					if ($this->displayUnapproved) {
						$tc1 = Page::getByID(HOME_CID, "RECENT");
					} else {
						$tc1 = Page::getByID(HOME_CID, "ACTIVE");
					}
					$niRow = array();
					$niRow['cvName'] = $tc1->getCollectionName();
					$niRow['cID'] = HOME_CID;
					$niRow['cvDescription'] = $tc1->getCollectionDescription();
					$niRow['cPath'] = $tc1->getCollectionPath();
					
					$ni = new AutonavBlockItem($niRow, 0);
					$ni->setCollectionObject($tc1);
					
					array_unshift($this->navArray, $ni);
				}
				
				/*
				
				if ($displayHeadPage) {				
					$niRow = array();
					$niRow['cvName'] = $tc1->getCollectionName();
					$niRow['cID'] = $row['cID'];
					$niRow['cvDescription'] = $tc1->getCollectionDescription();
					$niRow['cPath'] = $tc1->getCollectionPath();
					
					$ni = new AutonavBlockItem($niRow, 0);
					$level++;
					$ni->setCollectionObject($tc1);
					
					array_unshift($this->navArray, $ni);
				}
				*/
				
			}
			
			return $this->navArray;
		}
		
	}
	
	
	class AutonavBlockItem extends Concrete5_Controller_Block_AutonavItem {
		
		function getURL(){
			if( REQUEST_RESOLVE_WILDCARDS === true && !(REQUEST_SUB_DOMAIN === null) ){
				$this->cPath = str_replace( REQUEST_RESOLVE_WILDCARDS_PATH . '/' . REQUEST_SUB_DOMAIN, '', $this->cPath);
			}elseif( defined('REQUEST_SUB_DOMAIN_IS_ROOT') ){
				$this->cPath = str_replace( REQUEST_RESOLVE_ROOT_PATH, '', $this->cPath);
			}
			
			return parent::getURL();
		}
		
	}