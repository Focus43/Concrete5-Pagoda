<? defined('C5_EXECUTE') or die("Access Denied."); ?>
<p><?=t('Total form submissions: <strong>%s</strong> (<strong>%s</strong> total). <a href="%s">View Form Results</a>.', $totalFormSubmissions, $totalFormSubmissionsToday, $this->url('/dashboard/reports/forms'))?></p>