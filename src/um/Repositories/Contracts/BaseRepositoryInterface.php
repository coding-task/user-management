<?php

namespace UM\Repositories\Contracts;

interface BaseRepositoryInterface
{
    /**
     * Retrieve all data of repository.
     *
     * @param array $columns
     *
     * @return mixed
     */
    public function all(array $columns = ['*']);

    /**
     * Crate new entity in repository.
     *
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update a entity in repository by id.
     *
     * @param array $attributes
     * @param int $id
     *
     * @return mixed
     */
    public function update(array $attributes, int $id);

    /**
     * Delete a entity in repository by id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function delete(int $id);

    /**
     * Find data by id.
     *
     * @param int $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find(int $id, array $columns = ['*']);
}
