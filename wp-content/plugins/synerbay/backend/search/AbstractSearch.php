<?php


namespace SynerBay\Search;


abstract class AbstractSearch
{
    protected $query;

    private bool $orderByProcessed = false;

    protected array $searchAttributes = [];

    public function __construct(array $searchAttributes = [])
    {
        $this->searchAttributes = $searchAttributes;
        $this->prepareQuery();
    }

    public function search($output = ARRAY_A)
    {
        global $wpdb;

        $this->processOrderBy();

//        var_dump($this->query);die;

        return $wpdb->get_results($this->query, $output);
    }

    public function paginate($limit, $page = 1, $output = ARRAY_A)
    {

        $this->processOrderBy();

        $this->query .= " LIMIT $limit";

        if ($page && $page !== 1) {
            $offset = $page * $limit;
            $this->query .= " OFFSET $offset";
        }

        return $this->search($output);
    }

    private function processOrderBy()
    {
        if (!$this->orderByProcessed && array_key_exists('order', $this->searchAttributes)) {

            $orderData = $this->searchAttributes['order'];

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

    abstract protected function prepareQuery();
}