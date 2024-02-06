<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\View\ViewRenderer;
use App\Class\Redirector;

class CategoryController
{
  private CategoryService $categoryService;
  private ViewRenderer $viewRenderer;
  private Redirector $redirector;

  public function __construct(CategoryService $categoryService, ViewRenderer $viewRenderer, Redirector $redirector)
  {
    $this->categoryService = $categoryService;
    $this->viewRenderer = $viewRenderer;
    $this->redirector = $redirector;
  }

  public function createCategory($request)
  {
    $name = $request['name'] ?? '';

    if (empty($name)) {
      $this->redirector->redirect('category', ['error' => 'Category name cannot be empty']);
      return;
    }

    try {
      $this->categoryService->createCategory($name);
      $this->redirector->redirect('category', ['success' => 'Category created successfully']);
    } catch (\Exception $e) {
      $this->redirector->redirect('category', ['error' => $e->getMessage()]);
    }
  }

  public function updateCategory($request)
  {
    $categoryId = $request['id'] ?? null;
    $name = $request['name'] ?? '';

    if (is_null($categoryId) || empty($name)) {
      $this->redirector->redirect('category', ['error' => 'Invalid category data']);
      return;
    }

    try {
      $category = $this->categoryService->getCategoryById($categoryId);
      if (!$category) {
        $this->redirector->redirect('category', ['error' => 'Category not found']);
        return;
      }

      $category->setName($name);
      $this->categoryService->updateCategory($category);
      $this->redirector->redirect('category', ['success' => 'Category updated successfully']);
    } catch (\Exception $e) {
      $this->redirector->redirect('category', ['error' => $e->getMessage()]);
    }
  }

  public function deleteCategory($request)
  {
    $categoryId = $request['id'] ?? null;

    if (is_null($categoryId)) {
      $this->redirector->redirect('category', ['error' => 'Invalid category ID']);
      return;
    }

    try {
      $category = $this->categoryService->getCategoryById($categoryId);
      if (!$category) {
        $this->redirector->redirect('category', ['error' => 'Category not found']);
        return;
      }

      $this->categoryService->deleteCategory($category);
      $this->redirector->redirect('category', ['success' => 'Category deleted successfully']);
    } catch (\Exception $e) {
      $this->redirector->redirect('category', ['error' => $e->getMessage()]);
    }
  }
}
