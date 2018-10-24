<?php

/**
 * kreuzfahrt.de Modules Set
 *
 * Copyright (c) 2016 Die Schittigs
 *
 * @license LGPL-3.0+
 */

namespace DieSchittigs\SttgsRedirect\Models;

class RedirectModel extends \Contao\Model {

	/**
	 * Table name
	 * @var string
	 */
	protected static $strTable = 'tl_redirect';

	public static function findPublishedByUrl($strUrl, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.url=?");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published";
		}

		$arrOptions['order'] = "$t.sorting";

		return static::findBy($arrColumns, array($strUrl), $arrOptions);
	}

	public static function findPublishedByType($strType, array $arrOptions=array())
	{
		$t = static::$strTable;
		$arrColumns = array("$t.type=?");

		if (!static::isPreviewMode($arrOptions))
		{
			$time = \Date::floorToMinute();
			$arrColumns[] = "($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'" . ($time + 60) . "') AND $t.published='1'";
		}

		$arrOptions['order'] = "$t.sorting";

		return static::findBy($arrColumns, array($strType), $arrOptions);
	}
}
