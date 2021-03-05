<?php

namespace SynerBay\Traits;

trait Search
{
    protected string $query;

    protected bool $paginationMode = false;

    protected array $columns = ['*'];

    protected array $join = [];

    protected array $whereParameters = [];

    private bool $orderByProcessed = false;

    public function paginate(array $searchAttributes = [], $limit = 25, $page = 1, $output = ARRAY_A)
    {
        global $rowPerPage, $currentPage, $allRow, $lastPage;

        $this->paginationMode = true;

        $this->prepareQuery($searchAttributes);

        $this->buildQuery($this->columns);

        $rowPerPage = $limit;

        $currentPage = $page;

        $this->processOrderBy($searchAttributes);

        $this->query .= " LIMIT $limit";

        if ($page && $page !== 1) {
            $offset = ($page * $limit) - $limit;
            $this->query .= " OFFSET $offset";
        }

//        var_dump($this->query);die;

        $searchResult = $this->search($searchAttributes, $output);

        $allRow = $this->getAllResultRowNum();
        $lastPage = ceil($allRow / $rowPerPage);

        return $searchResult;
    }

    private function getAllResultRowNum()
    {
        global $wpdb;

        $rows = $wpdb->get_results('SELECT FOUND_ROWS() as rowNumber', ARRAY_A);

        return (int)$rows[0]['rowNumber'];
    }

    private function processOrderBy(array $searchAttributes = [])
    {
        if (!$this->orderByProcessed && array_key_exists('order', $searchAttributes)) {

            $orderData = $searchAttributes['order'];

            if (!array_key_exists('columnName', $orderData)) {
                die('Missing parameter order by!');
            }

            $columnName = $orderData['columnName'];

            if (!array_key_exists('direction', $orderData)) {
                $direction = 'ASC';
            } else {
                $direction = $orderData['direction'];
            }

            $this->query .= " ORDER BY $columnName $direction";

            $this->orderByProcessed = true;
        }
    }

    public function search(array $searchAttributes = [], $output = ARRAY_A)
    {
        global $wpdb;

        if (!$this->paginationMode) {

            $this->prepareQuery($searchAttributes);

            $this->buildQuery($this->columns);

            $this->processOrderBy();
        }

        return $wpdb->get_results($this->query, $output);
    }

    protected function addWhereParameter(string $where, $arg, string $connectionType = 'and')
    {
        $this->whereParameters[] = [
            'where'          => $where,
            'arg'            => $arg,
            'connectionType' => $connectionType,
        ];
    }

    protected function addJoin($command)
    {
        $this->join[] = $command;
    }

    private function buildQuery($cols = ['*'])
    {
        global $wpdb;

        $columns = '';

        if ($this->paginationMode) {
            $columns = 'SQL_CALC_FOUND_ROWS ';
        }

        $columns .= implode(', ', $cols);

        $query = 'SELECT '.$columns.' FROM ';

        $query .= $this->getBaseTable();

        $query .= $this->buildJoins();

        $query .= $this->buildWhereString();

        $whereParameters = $this->buildWhereParameters();

        $this->query =  $wpdb->prepare($query, ...$whereParameters);
    }

    private function buildJoins()
    {
        $ret = '';

        if (count($this->join)) {
            $ret = sprintf(' %s ', implode(' ', $this->join));
        }

        return $ret;
    }

    private function buildWhereString()
    {
        $ret = [];

        if (count($this->whereParameters)) {
            $i = 0;
            foreach ($this->whereParameters as $parameter) {
                $tmp = $parameter['where'];

                if ($i != 0 && $i < count($this->whereParameters)) {
                    if (empty($parameter['connectionType'])) {
                        continue;
                    }

                    $tmp = $parameter['connectionType'] . ' ' . $tmp;
                }

                $ret[] = $tmp;
                $i++;
            }
        }

        $retTmp = '';

        if (count($ret)) {
            $retTmp = sprintf(' WHERE %s', implode(' ', $ret));
            unset($ret);
        }

        return $retTmp;
    }

    private function buildWhereParameters()
    {
        $ret = [];

        if (count($this->whereParameters)) {
            foreach ($this->whereParameters as $parameter) {
                if (!is_array($parameter['arg'])) {
                    $ret[] = $parameter['arg'];
                } else {
                    foreach ($parameter['arg'] as $arg) {
                        $ret[] = $arg;
                    }
                }
            }
        }

        return $ret;
    }

    protected function getBaseTable()
    {
        global $wpdb;

        return $wpdb->prefix . $this->getBaseTableName();
    }

    protected function buildInPlaceholderFromArrayToWhere(array $arr)
    {
        $inStrArr = array_fill( 0, count( $arr ), '%s' ); // create a string of %s - one for each array value. This creates array( '%s', '%s', '%s' )
        return join( ',', $inStrArr ); // now turn it into a comma separated string. This creates "%s,%s,%s"
    }
}

