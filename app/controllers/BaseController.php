<?php

class BaseController extends Controller
{

    public $tables = array();

    public function __construct()
    {
        /* Set the Language Based on Agent Browser */
        $languages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
        $lang = substr($languages[0], 0, 2);
        App::setLocale($lang);

        /* Load Assets */
        Asset::add('bootstraptheme', 'assets/css/bootstrap-spacelab.css');
        Asset::add('bootstrapresponsive', 'assets/css/bootstrap-responsive.css');
        Asset::add('charisma', 'assets/css/charisma-app.css');
        Asset::add('uniform', 'assets/css/uniform.default.css');
        Asset::add('elfinder', 'assets/css/elfinder.min.css');
        Asset::add('opaicons', 'assets/css/opa-icons.css');
        Asset::add('famfam', 'assets/css/famfam.css');
        Asset::add('jqueryloadermin', 'assets/css/jquery.loader-min.css');
        Asset::add('reportula', 'assets/css/reportula.css');

        Asset::add('jquery', 'assets/js/jquery-2.0.3.min.js');
        Asset::add('datatables', 'assets/js/jquery.dataTables.min.js','jquery');
        Asset::add('jqueryloader', 'assets/js/jquery.loader-min.js', 'jquery');
        Asset::add('main', 'assets/js/main.js', 'TableTools');
        Asset::add('modal', 'assets/js/bootstrap-modal.js', 'jquery');

        /* Get Monolog instance from Laravel */
        $monolog = Log::getMonolog();
        /* Add the FirePHP handler */
        $monolog->pushHandler(new \Monolog\Handler\FirePHPHandler());

        /* Resolve Database Names Myslq and Postgres */
        if ( Config::get('database.default')=='mysql' ) {
          $this->tables = array( 'job' => 'Job',
                                  'client' => 'Client',
                                  'files' => 'Files',
                                  'media' => 'Media',
                                  'pool' => 'Pool',
                                  'path' => 'Path',
                                  'filename' => 'Filename',
                                  'file' => 'File',
                                  'jobfiles' => 'JobFiles',
                                  'jobmedia' => 'JobMedia',

          );
         } else {
          $this->tables = array( 'job' => 'job',
                                  'client' => 'client',
                                  'files' => 'files',
                                  'media' => 'media',
                                  'pool' => 'pool',
                                  'path' => 'path',
                                  'filename' => 'filename',
                                  'file' => 'file',
                                  'jobfiles' => 'jobfiles',
                                  'jobmedia' => 'jobmedia'
          );
         }



    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout)) {
            $this->layout = View::make($this->layout)->with('js_config', $js_config);;
        }
    }

    /**
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     *
     * @access	public
     * @param	mixed	// will be cast as int
     * @return string
     */

    public function byte_format($num, $precision = 1)
    {

        if ($num >= 1000000000000) {
            $num = round($num / 1099511627776, $precision);
            $unit = 'Tb';
        } elseif ($num >= 1000000000) {
            $num = round($num / 1073741824, $precision);
            $unit = 'Gb';
        } elseif ($num >= 1000000) {
            $num = round($num / 1048576, $precision);
            $unit ='Mb';
        } elseif ($num >= 1000) {
            $num = round($num / 1024, $precision);
            $unit = 'Kb';
        } else {
            $unit = 'b';

            return number_format($num).' '.$unit;
        }

        return number_format($num, $precision).' '.$unit;
    }

function explodeTree($array, $delimiter = '_', $baseval = false)
{
  if(!is_array($array)) return false;
  $splitRE   = '/' . preg_quote($delimiter, '/') . '/';
  $returnArr = array();
  foreach ($array as $key => $val) {
      // Get parent parts and the current leaf
      $parts  = preg_split($splitRE, $key, -1, PREG_SPLIT_NO_EMPTY);
      $leafPart = array_pop($parts);

      // Build parent structure
      // Might be slow for really deep and large structures
      $parentArr = &$returnArr;
      foreach ($parts as $part) {
          if (!isset($parentArr[$part])) {
              $parentArr[$part] = array();
          } elseif (!is_array($parentArr[$part])) {
              if ($baseval) {
                  $parentArr[$part] = array('__base_val' => $parentArr[$part]);
              } else {
                  $parentArr[$part] = array();
              }
          }
          $parentArr = &$parentArr[$part];
      }

      // Add the final part to the structure
      if (empty($parentArr[$leafPart])) {
          $parentArr[$leafPart] = $val;
      } elseif ($baseval && is_array($parentArr[$leafPart])) {
          $parentArr[$leafPart]['__base_val'] = $val;
      }
  }

  return $returnArr;
}

function MakeMenu($items, $level = 0)
{
    $ret = "";
    $indent = str_repeat(" ", $level * 2);
    $ret .= sprintf("%s<ul>\n", $indent);
    $indent = str_repeat(" ", ++$level * 2);
    foreach ($items as $item => $subitems) {
        if (!is_numeric($item)) {
            $ret .= sprintf("%s<li><a href=''>%s<a/>", $indent, $item);
        }
        if (is_array($subitems)) {
            $ret .= "\n";
            $ret .= $this->MakeMenu($subitems, $level + 1);
            $ret .= $indent;
        } elseif (strcmp($item, $subitems)) {
            $ret .= sprintf("%s<li><a href=''>%s<a/>", $indent, $subitems);
        }
        $ret .= sprintf("</li>\n", $indent);
    }
    $indent = str_repeat(" ", --$level * 2);
    $ret .= sprintf("%s</ul>\n", $indent);

    return($ret);
}

function htmlTable($array, $table = true)
{
    $out = '';
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (!isset($tableHeader)) {
                $tableHeader =
                    '<tr><th>' .
                    implode('</th><th>', array_keys($value)) .
                    '</th></tr>';
            }
            array_keys($value);
            $out .= '<tr>';
            $out .= $this->htmlTable($value, false);
            $out .= '</tr>';
        } else {
            $out .= "<td>$value</td>";
        }
    }

    if ($table) {
        return '<table>' . $tableHeader . $out . '</table>';
    } else {
        return $out;
    }
}


function array2table($array, $recursive = false, $null = '&nbsp;')
{
    // Sanity check
    if (empty($array) || !is_array($array)) {
        return false;
    }

    if (!isset($array[0]) || !is_array($array[0])) {
        $array = array($array);
    }

    // Start the table
    $table = "<table>\n";

    // The header
    $table .= "\t<tr>";
    // Take the keys from the first row as the headings
    foreach (array_keys($array[0]) as $heading) {
        $table .= '<th>' . $heading . '</th>';
    }
    $table .= "</tr>\n";

    // The body
    foreach ($array as $row) {
        $table .= "\t<tr>" ;
        foreach ($row as $cell) {
            $table .= '<td>';

            // Cast objects
            if (is_object($cell)) { $cell = (array) $cell; }

            if ($recursive === true && is_array($cell) && !empty($cell)) {
                // Recursive mode
                $table .= "\n" . array2table($cell, true, true) . "\n";
            } else {
                $table .= (strlen($cell) > 0) ?
                    htmlspecialchars((string) $cell) :
                    $null;
            }

            $table .= '</td>';
        }

        $table .= "</tr>\n";
    }

    $table .= '</table>';
    return $table;
}

}
