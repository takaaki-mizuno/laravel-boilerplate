<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\BaseRequest;
use App\Repositories\AdminUserRepositoryInterface;

class AdminUserRequest extends BaseRequest
{

    /** @var \App\Repositories\AdminUserRepositoryInterface */
    protected $adminUserRepository;

    public function __construct(AdminUserRepositoryInterface $adminUserRepository)
    {
        $this->adminUserRepository = $adminUserRepository;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->adminUserRepository->rules();
    }

    public function messages()
    {
        return $this->adminUserRepository->messages();
    }

}
