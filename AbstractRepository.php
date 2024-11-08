<?php


use App\Criteria\RequestCriteria;
use App\Presenter\AbstractPresenter;
use Illuminate\Container\Container as Application;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Exceptions\RepositoryException;

abstract class AbstractRepository extends BaseRepository
{
    protected ConnectionInterface $connection;

    protected $fieldSearchable = [
        'id',
        'name'
    ];

    private bool $withPermissions = false;

    public function __construct(Application $app)
    {
        $this->connection = $app->make(ConnectionInterface::class);
        parent::__construct($app);
    }

    public function paginateWhere(array $where, $limit = null, $columns = ['*'], $method = 'paginate')
    {
        $this->applyConditions($where);

        return $this->paginate($limit, $columns, $method);
    }

    public function findOrNew($id, $columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();
        $model = $this->model->findOrNew($id, $columns);
        if (!$model->exists) {
            $key = $model->getKeyName();
            $model->$key = $id;
        }
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function withOnly($relations)
    {
        $this->model = $this->model->withOnly($relations);

        return $this;
    }

    /**
     * @return $this
     *
     * @throws RepositoryException
     */
    public function enableRequestCriteria(Request $request): self
    {
        $this->pushCriteria(new RequestCriteria($request));

        return $this;
    }

    /**
     * @return $this
     */
    public function addMeta(array $meta): self
    {
        if ($this->presenter instanceof AbstractPresenter) {
            $this->presenter->setMeta($meta);
        }

        return $this;
    }

    /**
     * @param  array|string  $includes
     * @return $this
     */
    public function addIncludes($includes, bool $replace = true): self
    {
        if ($this->presenter instanceof AbstractPresenter) {
            $this->presenter->setIncludes($includes, $replace);
        }

        return $this;
    }

    /**
     * @param  array|string  $excludes
     * @return $this
     */
    public function addExcludes($excludes): self
    {
        if ($this->presenter instanceof AbstractPresenter) {
            $this->presenter->setExcludes($excludes);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function beginTransaction(): self
    {
        $this->connection->beginTransaction();

        return $this;
    }

    /**
     * @return $this
     */
    public function commit(): self
    {
        $this->connection->commit();

        return $this;
    }

    /**
     * @return $this
     */
    public function rollback(): self
    {
        $this->connection->rollBack();

        return $this;
    }

    public function parserResult($result)
    {
        if ($this->withPermissions()) {
            $this->addIncludes(['permissions'], false);
        }

        return parent::parserResult($result);
    }

    public function includePermissions(): self
    {
        $this->withPermissions = true;

        return $this;
    }

    public function copyQuery(): Builder
    {
        return $this->applyScope()->applyCriteria()->skipCriteria()->getModel()->clone();
    }

    private function withPermissions(): bool
    {
        return $this->withPermissions;
    }
}
