import { render, fireEvent, screen } from "@testing-library/svelte"
import { describe, it, expect, vi } from "vitest"
import Dropdown from "../ui/Dropdown.svelte"
import Modal from "../ui/Modal.svelte"
import Menu from "../ui/Menu.svelte"
import Pagination from "../ui/Pagination.svelte"
import Metrics from "../ui/Metrics.svelte"
import Tags from "../ui/Tags.svelte"

describe("Nova generic components (#8)", () => {
  it("Dropdown opens on trigger click and closes on Escape", async () => {
    const { queryByTestId } = render(Dropdown, { label: "Actions" })
    await fireEvent.click(screen.getByTestId("nova-dropdown-trigger"))
    expect(screen.getByTestId("nova-dropdown-panel")).toBeTruthy()
    await fireEvent.keyDown(window, { key: "Escape" })
    expect(queryByTestId("nova-dropdown-panel")).toBeNull()
  })

  it("Modal closes on Escape and traps focus", async () => {
    const { rerender, queryByTestId } = render(Modal, { open: true, title: "Delete" })
    expect(screen.getByTestId("nova-modal-panel")).toBeTruthy()
    await fireEvent.keyDown(window, { key: "Escape" })
    await rerender({ open: false })
    expect(queryByTestId("nova-modal-panel")).toBeNull()
  })

  it("Menu roving tabindex keeps one tab stop and moves with arrows", async () => {
    const items = [
      { label: "Edit", value: "edit" },
      { label: "Delete", value: "delete" },
    ]
    render(Menu, { items, value: "edit" })
    const entry = screen.getAllByTestId("nova-menu-item")
    expect(entry).toHaveLength(2)
    expect(entry[0].getAttribute("tabindex")).toBe("0")
    expect(entry[1].getAttribute("tabindex")).toBe("-1")
    entry[0].focus()
    await fireEvent.keyDown(entry[0], { key: "ArrowDown" })
    expect(document.activeElement).toBe(entry[1])
  })

  it("Pagination renders pages and calls onNavigate", async () => {
    const onNavigate = vi.fn()
    const { container } = render(Pagination, { page: 2, last: 5, onNavigate })
    const pageButtons = container.querySelectorAll("nav[aria-label=Pagination] button")
    expect(pageButtons.length).toBeGreaterThan(1)
    await fireEvent.click(container.querySelector("nav[aria-label=Pagination] button[aria-label=\"Next page\"]"))
    expect(onNavigate).toHaveBeenCalledWith(3)
  })

  it("Metrics renders a card per item", () => {
    const items = [
      { key: "users", label: "Users", value: 12 },
      { key: "posts", label: "Posts", value: 7 },
    ]
    render(Metrics, { items })
    expect(screen.getAllByTestId("nova-metric")).toHaveLength(2)
  })

  it("Tags renders chips and removes on click", async () => {
    const onRemove = vi.fn()
    render(Tags, { tags: ["alpha", "beta"], onRemove })
    expect(screen.getAllByTestId("nova-tag")).toHaveLength(2)
    await fireEvent.click(screen.getAllByTestId("nova-tag-remove")[0])
    expect(onRemove).toHaveBeenCalledWith("alpha", 0)
  })
})
