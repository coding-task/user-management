<?php

namespace UM\Repositories\Eloquent;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Application;
use UM\Repositories\Contracts\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /** @var Application */
    protected $app;

    /** @var string */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Application $app
     *
     * @throws RepositoryException
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Create a Model Instance.
     *
     * @return Model
     *
     * @throws RepositoryException
     */
    public function makeModel() : Model
    {
        $model = $this->app->make($this->model());

        if (!$model instanceof Model) {
            throw new RepositoryException(
                sprintf("Class %s must be an instance of Illuminate\Database\Eloquent\Model", $this->model)
            );
        }

        return $this->model = $model;
    }

    /**
     * Reset Model Instance.
     *
     * @throws RepositoryException
     */
    public function resetModel()
    {
        $this->makeModel();
    }

    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all(array $columns=['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * Save a new entity to repository.
     *
     * @param array $attributes
     *
     * @return mixed
     *
     * @throws RepositoryException
     */
    public function create(array $attributes) : Model
    {
        $model = $this->model->newInstance($attributes);
        $model->save();

        $this->resetModel();

        return $model;
    }

    /**
     * Update entity in repository by id.
     *
     * @param array $attributes
     * @param int $id
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function update(array $attributes, int $id)
    {
        $model = $this->model->findOrFail($id);

        $model->fill($attributes);
        $model->save();

        $this->resetModel();

        return $model;
    }

    /**
     * Find data by id.
     *
     * @param int $id
     * @param array $columns
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function find(int $id, array $columns = ['*']) : Model
    {
        $model = $this->model->findOrFail($id, $columns);

        $this->resetModel();

        return $model;
    }

    /**
     * Delete a entity in a repository by id.
     *
     * @param int $id
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function delete(int $id)
    {
        $model = $this->find($id);

        $deleted = $model->delete();

        return $deleted;
    }

    /**
     * Specify Model Class Name.
     *
     * @return string
     */
    abstract function model();
}
